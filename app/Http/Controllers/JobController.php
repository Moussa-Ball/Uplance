<?php

namespace App\Http\Controllers;

use App\Job;
use App\Skill;
use App\Category;
use App\Events\NewJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Requests\PostJobRequest;
use App\Review;
use App\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use PragmaRX\Countries\Package\Countries;

class JobController extends Controller
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
    public function index()
    {
        SEOMeta::setTitle('Find Work');
        return view('jobs.index');
    }

    /**
     * Allows to search jobs.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function search(Request $request)
    {
        $keywords = ($request->get('q')) ? $request->get('q') : '*';
        $location = $request->get('location');
        $category =  $request->get('category');
        $minimum =  ($request->get('minimum') == '0') ? 0 : $request->get('minimum');
        $maximum =  ($request->get('maximum') == '0') ? 10000000 : $request->get('maximum');
        $project_type = $request->get('project_type');
        $skills =  $request->get('skills');
        $sort_by =  $request->get('sort_by');

        $job = new Job();
        $job = $job->search($keywords);

        if ($location) {
            $job = $job->where('country', \PragmaRX\Countries\Package\Countries::where('name.common', $location)
                ->first()->cca2);
        }

        if ($category) {
            $category = Category::where('name', 'LIKE', '%' . $category . '%')->first();
            $job = $job->where('category_id', $category->id);
        }

        $job = $job->where('minimum', '>=', $minimum);
        $job = $job->where('maximum', '<=', $maximum);

        if ($project_type) {
            $job = $job->where('project_type', strtolower($project_type));
        }

        if ($skills) {
            $job = $job->whereIn('skills', explode(' ', strtolower($skills)));
        }

        if ($sort_by == 'Newest') {
            $job = $job->orderBy('created_at', 'DESC');
        } elseif ($sort_by == 'Oldest') {
            $job = $job->orderBy('created_at', 'ASC');
        }

        return $job->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('client');
        SEOMeta::setTitle('Post a Job');
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostJobRequest $request)
    {
        // Don't authorize this method, if the current user is not a client.
        $this->authorize('client');

        // get category
        $category = Category::where('name', $request->category)->first();

        // create job.
        $job = Job::create([
            'project_name' => $request->project_name,
            'minimum' => intval($request->minimum),
            'maximum' => intval($request->maximum),
            'location' => $request->location,
            'project_type' => $request->project_type,
            'skills' => $request->skills,
            'city' => $request->user()->city,
            'description' => $request->description,
            'country' => $request->user()->country,
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        // Sync job tag.
        $job->categories()->sync($category);

        // Sync skills tags.
        $skills = $request->input('skills');
        $skills = explode(',', $skills);

        if ($skills && is_array($skills)) {
            foreach ($skills as $skill) {
                $skill_exist = Skill::where('name', $skill)->first();
                if (!$skill_exist) {
                    Skill::create(['name' => $skill, 'slug' => Str::slug($skill)]);
                }
            }
            $request->user()->retag($skills);
        } elseif ($skills && !is_array($skills)) {
            $request->user()->retag([$skills]);
        }

        broadcast(new NewJob($job));

        return response()->json([
            'attachable_id' => $job->id,
            'attachable_type' => Job::class,
            'message' => 'Your project has been published.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $category = $job->categories()->first();
        if (!$category) return abort(404);
        $reviews = Review::where('from_id', \Auth::id())->where('rated', true)->paginate(10);

        $rating = null;
        $rate_title = null;

        foreach ($reviews as $review) {
            $rating += $review->rating;
        }
        $rating = $rating / 10;

        if ($rating > 5)
            $rating = 5;

        SEOMeta::setTitle($job->project_name . ' - ' . $category->name);
        SEOMeta::setDescription($job->description);
        SEOTools::setCanonical(route('freelancers.show', $job->hashid));
        SEOMeta::addMeta('robots', 'noarchive', 'property');

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle($job->project_name . ' - ' . $category->name);
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription($job->description);
        SEOTools::opengraph()->setUrl(route('freelancers.show', $job->hashid));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle($job->project_name . ' - ' . $category->name);
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription($job->description);
        SEOTools::twitter()->setUrl(route('freelancers.show', $job->hashid));

        return view('jobs.show', compact('job', 'reviews', 'rating', 'rate_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->authorize('client');
        $encoded_id = $id;
        $id = Hashids::connection(Job::class)->decode($id);
        $job = Job::where('id', $id)->first();
        if (!$job) return abort(404);
        $this->authorize('owner', $job->user_id);
        SEOMeta::setTitle('Edit your project: ' . $job->project_name);
        $job = [
            'id' => $encoded_id,
            'project_name' => $job->project_name,
            'project_type' => $job->project_type,
            'category' => ($job->categories()->first()) ? $job->categories()->first()->name : '',
            'minimum' => $job->minimum,
            'maximum' => $job->maximum,
            'location' => $job->location,
            'skills' => $job->skills,
            'city' => $request->user()->city,
            'description' => $job->description,
            'country' => $request->user()->country,
        ];
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        $this->authorize('client');
        $this->authorize('owner', $job->user_id);

        // get category
        $category = Category::where('name', $request->category)->first();

        // update job.
        $job->update([
            'project_name' => $request->project_name,
            'minimum' => $request->minimum,
            'maximum' => $request->maximum,
            'location' => $request->location,
            'skills' => $request->skills,
            'description' => $request->description,
            'country' => $request->user()->country,
            'city' => $request->user()->city,
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        // Sync job tag.
        $job->categories()->sync($category);

        // Sync skills tags.
        $skills = $request->input('skills');
        $skills = explode(',', $skills);

        if ($skills && is_array($skills)) {
            foreach ($skills as $skill) {
                $skill_exist = Skill::where('name', $skill)->first();
                if (!$skill_exist) {
                    Skill::create(['name' => $skill, 'slug' => Str::slug($skill)]);
                }
            }
            $request->user()->retag($skills);
        } elseif ($skills && !is_array($skills)) {
            $request->user()->retag([$skills]);
        }

        return response()->json('Your project has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = Hashids::connection(Job::class)->decode($id);
        if (!$id) return abort(404);
        $job = Job::where('id', $id)->first();
        if ($job) {
            $this->authorize('client');
            $this->authorize('owner', $job->user_id);
            $job->delete();
            return redirect()->back()->with('success', 'Your project has been archived.');
        }
        return abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\View
     */
    public function manage(Request $request)
    {
        $this->authorize('client');
        SEOMeta::setTitle('Manage Jobs');
        $jobs = Job::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'DESC')->paginate(10);
        return view('jobs.manage', compact('jobs'));
    }
}
