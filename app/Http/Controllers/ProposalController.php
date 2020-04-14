<?php

namespace App\Http\Controllers;

use App\Job;
use App\Proposal;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Requests\ProposalRequest;
use App\Notifications\ProposalRejected;
use App\Notifications\ProposalSend;
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
        $this->authorize('freelancer');
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
            'credit' => \Auth::user()->credit
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

        $this->authorize('freelancer');
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

        $proposal->job->user->notify(new ProposalSend($proposal));
        $proposal->user->credit -= 2;
        $proposal->user->save();

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
        $this->authorize('client');
        $encoded_id = $id;
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        if (!$job) return abort(404);
        $this->authorize('owner', $job->user_id);
        $proposals = Proposal::where('job_id', $job->id)->orderBy('created_at', 'DESC')->paginate(10);
        SEOMeta::setTitle('Manage Bidders');
        return view('proposals.show', compact('proposals', 'job'));
    }

    /**
     * Delete proposal.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Job $job, Proposal $proposal)
    {
        $this->authorize('client');
        $this->authorize('owner', $job->user_id);
        $this->authorize('not-accepted', $proposal);

        if ($proposal->accepted) return abort(402);
        $proposal->user->notify(new ProposalRejected($proposal));
        $proposal->delete();

        return redirect()->back()->with('success', "The freelancer's proposal was refused.");
    }
}
