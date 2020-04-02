<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use App\Article;
use App\Category;
use App\Sponsor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (\Auth::check() && \Auth::user()->current_account == 'freelancer')
            return redirect()->route('jobs.index');
        elseif (\Auth::check() && \Auth::user()->current_account == 'client')
            return redirect()->route('freelancers.index');

        $sponsors = Sponsor::get();
        $job_posted = Job::count();

        $posts = Article::orderBy('created_at', 'DESC')->limit(3)->get();

        $freelancers = User::where('account_type', 'freelancer')
            ->where('skills', '!=', null)->count();

        $recent_jobs = Job::orderBy('created_at', 'DESC')->limit(5)->get();

        $highest_rated_freelancers = User::where('account_type', 'freelancer')
            ->where('skills', '!=', null)
            ->where('job_success', '>=', 90)
            ->where('recommendation', '>=', 90)
            ->where('on_time', '>=', 90)
            ->where('on_budget', '>=', 90)
            ->orderBy('global_rank', 'DESC')
            ->limit(6)->get();

        $developer = User::where('email', 'moiseball20155@gmail.com')
            ->where('skills', '!=', null)
            ->where('job_success', '>=', 90)
            ->where('recommendation', '>=', 90)
            ->where('on_time', '>=', 90)
            ->where('on_budget', '>=', 90)
            ->first();

        $jobsInSanFrancisco = Job::where('country', 'United States')
            ->where('city', 'San Francisco')
            ->count();

        $jobsInNewYork = Job::where('country', 'United States')
            ->where('city', 'New York')
            ->count();

        $jobsInLosAngeles = Job::where('country', 'United States')
            ->where('city', 'Los Angeles')
            ->count();

        $jobsInMiami = Job::where('country', 'United States')
            ->where('city', 'Miami')
            ->count();

        $jobsInCity = [
            $jobsInSanFrancisco, $jobsInNewYork, $jobsInLosAngeles, $jobsInMiami
        ];

        return view('home', [
            'posts' => $posts,
            'sponsors' => $sponsors,
            'tags' => Category::limit(8)->orderBy('created_at', 'ASC')->get(),
            'developer' => $developer,
            'job_posted' => $job_posted,
            'jobsInCity' => $jobsInCity,
            'freelancers' => $freelancers,
            'recent_jobs' => $recent_jobs,
            'highest_rated_freelancers' => $highest_rated_freelancers
        ]);
    }

    public function getFreelancerFromTag($slug)
    {
        $tag = Category::where('slug', $slug)->first();
        $freelancers = $tag->users()
            ->where('account_type', 'freelancer')
            ->where('skills', '!=', null)
            ->paginate(40);
        return view('freelancers-tag', compact('tag', 'freelancers'));
    }

    public function searchJob(Request $request)
    {
        $search = ($request->get('q')) ? $request->get('q') : '*';
        $location = $request->get('location');

        $job = new Job();
        $job = $job->search($search);

        if ($location) {
            $job = $job->where('country', strtolower($location));
        }

        return view('search-job', [
            'jobs' => $job->orderBy('created_at', 'DESC')->paginate(10),
        ]);
    }

    public function jobsInSanFrancisco()
    {
        $title = 'Jobs in San Francisco';
        $subtitle = 'List of Jobs in San Francisco';
        $jobs = Job::where('country', 'United States')
            ->where('city', 'San Francisco')->orderBy('created_at', 'DESC')->paginate(40);
        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInNewYork()
    {
        $title = 'Jobs in New York';
        $subtitle = 'List of Jobs in New York';
        $jobs = Job::where('country', 'United States')
            ->where('city', 'New York')->orderBy('created_at', 'DESC')->paginate(40);
        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInLosAngeles()
    {
        $title = 'Jobs in Los Angeles';
        $subtitle = 'List of Jobs in Los Angeles';
        $jobs = Job::where('country', 'United States')
            ->where('city', 'Los Angeles')->orderBy('created_at', 'DESC')->paginate(40);
        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInMiami()
    {
        $title = 'Jobs in Miami';
        $subtitle = 'List of Jobs in Miami';
        $jobs = Job::where('country', 'United States')
            ->where('city', 'Miami')->orderBy('created_at', 'DESC')->paginate(40);
        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function freelancersInUK()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'uk',
        ])->where('skills', '!=', null)->orderBy('created_at', 'DESC')->paginate(40);
        $title = 'Freelancers in United Kingdom';
        $subtitle = 'List of freelancers in United Kingdom';
        return view('search-freelancer', compact('freelancers', 'title', 'subtitle'));
    }

    public function freelancersInUSA()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'us',
        ])->where('skills', '!=', null)->orderBy('created_at', 'DESC')->paginate(40);
        $title = 'Freelancers in USA';
        $subtitle = 'List of freelancers in USA';
        return view('search-freelancer', compact('freelancers', 'title', 'subtitle'));
    }

    public function freelancersInFrance()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'fr',
        ])->where('skills', '!=', null)->orderBy('created_at', 'DESC')->paginate(40);
        $title = 'Freelancers in France';
        $subtitle = 'List of freelancers in France';
        return view('search-freelancer', compact('freelancers', 'title', 'subtitle'));
    }

    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        return view('faq');
    }

    public function terms()
    {
        return view('terms');
    }
}