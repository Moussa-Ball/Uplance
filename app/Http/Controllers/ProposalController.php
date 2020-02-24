<?php

namespace App\Http\Controllers;

use App\Job;
use App\Proposal;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Requests\ProposalRequest;
use App\Notifications\ProposalNotification;
use Artesaos\SEOTools\Facades\SEOMeta;

class ProposalController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $this->authorize('freelancer', $request->user());
        $encoded_id = $id;
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        if (!$job) return abort(404);
        $this->authorize('not-owner', $job->user_id);

        $proposal_exist = Proposal::withTrashed()->where([
            'job_id' => $job->id,
            'user_id' => $request->user()->id
        ])->first();

        if ($proposal_exist) {
            return redirect()->back()->with('warning', 'You have already submitted a proposal to this job.');
        }

        SEOMeta::setTitle('Submit a proposal');

        $job = [
            'id' => $encoded_id,
            'project_name' => $job->project_name,
            'project_type' => $job->project_type,
            'category' => ($job->categories()->first()) ? $job->categories()->first()->name : '',
            'minimum' => $job->minimum,
            'maximum' => $job->maximum,
            'location' => $job->location,
            'skills' => $job->skills,
            'description' => $job->description,
            'attachments' => $job->attachments()->get()
        ];

        $user = [
            'credit' => 30
        ];

        return view('proposals.create', compact('job', 'user'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProposalRequest $request, $id)
    {
        if ($request->user()->credit <= 1) {
            return response()->json(['error' => "You don't have enough credit to apply for a job."], 401);
        }

        $this->authorize('freelancer', $request->user());
        $encoded_id = $id;
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        if (!$job) return abort(404);
        $this->authorize('not-owner', $job->user_id);

        $proposal_exist = Proposal::where([
            'job_id' => $job->job_id,
            'user_id' => $request->user()->id
        ])->first();

        if ($proposal_exist) {
            return response()->json(['error' => "You have already submitted a proposal to this job."], 401);
        }

        $proposal = Proposal::firstOrCreate([
            'proposal_type' => $request->proposal_type,
            'hourly_amount' => $request->hourly_amount,
            'fixed_amount' => $request->fixed_amount,
            'cover_letter' => $request->cover_letter,
            'milestones' => $request->milestones,
            'due_date' => $request->due_date,
            'user_id' => $request->user()->id,
            'job_id' => $job->id,
        ]);

        $proposal->job->user->notify(new ProposalNotification($proposal));

        return response()->json([
            'attachable_id' => $proposal->id,
            'attachable_type' => Proposal::class,
            'message' => 'Your proposal has been sent to the client.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return Illuminate\Contracts\View\View
     */
    public function show(Request $request, $id)
    {
        $this->authorize('client', $request->user());
        $encoded_id = $id;
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        if (!$job) return abort(404);
        $this->authorize('owner', $job->user_id);
        $proposals = Proposal::where('job_id', $job->id)->paginate(10);
        SEOMeta::setTitle('Manage Bidders');
        return view('proposals.show', compact('proposals', 'job'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $job_id, $proposal_id)
    {
        $this->authorize('client', $request->user());

        $job_id = Hashids::connection(Job::class)->decode($job_id);
        if (!$job_id) return abort(404);

        $proposal_id = Hashids::connection(Proposal::class)->decode($proposal_id);
        if (!$proposal_id) return abort(404);

        $job = Job::where('id', $job_id)->first();
        if (!$job) return abort(404);

        $this->authorize('owner', $job->user_id);

        Proposal::where([
            'id' => $proposal_id,
            'job_id' => $job->id,
        ])->delete();

        return redirect()->back()->with('success', "The freelancer's proposal was refused.");
    }
}
