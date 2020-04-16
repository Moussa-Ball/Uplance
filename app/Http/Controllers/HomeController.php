<?php

namespace App\Http\Controllers;

use App\Article;
use App\Job;
use App\User;
use App\Category;
use App\Sponsor;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

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

        $jobsInSanFrancisco = Job::where('country', 'US')
            ->where('city', 'San Francisco')
            ->count();

        $jobsInNewYork = Job::where('country', 'US')
            ->where('city', 'New York')
            ->count();

        $jobsInLosAngeles = Job::where('country', 'US')
            ->where('city', 'Los Angeles')
            ->count();

        $jobsInMiami = Job::where('country', 'US')
            ->where('city', 'Miami')
            ->count();

        $jobsInCity = [
            $jobsInSanFrancisco, $jobsInNewYork, $jobsInLosAngeles, $jobsInMiami
        ];

        $countries = \PragmaRX\Countries\Package\Countries::all();

        SEOMeta::setTitle('Hire experts freelancers for any job, any time. - Uplance');
        SEOMeta::setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::setCanonical(url('/'));
        SEOMeta::addMeta('robots', 'noarchive', 'property');

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Hire experts freelancers for any job, any time. - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::opengraph()->setUrl(url('/'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Hire experts freelancers for any job, any time. - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::twitter()->setUrl(url('/'));

        return view('home', [
            'posts' => $posts,
            'sponsors' => $sponsors,
            'tags' => Category::limit(8)->orderBy('created_at', 'ASC')->get(),
            'developer' => $developer,
            'countries' => $countries,
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
            ->orderBy('global_rank', 'DESC')
            ->paginate(40);

        SEOMeta::setTitle('Best freelancers ' . $tag->name . ' - Uplance');
        SEOMeta::setDescription("With uplance, you don't even need to post a job, 
            you can hire experts directly for large projects.");
        SEOTools::setCanonical(route('freelancers.tag', $slug));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Freelancers by rank in ' . $tag->name . ' - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription("With uplance, you don't even need to post a job, 
            you can hire experts directly for large projects.");
        SEOTools::opengraph()->setUrl(route('freelancers.tag', $slug));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Freelancers by rank in ' . $tag->name . ' - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription("With uplance, you don't even need to post a job, 
            you can hire experts directly for large projects.");
        SEOTools::twitter()->setUrl(route('freelancers.tag', $slug));

        return view('freelancers-tag', compact('tag', 'freelancers'));
    }

    public function searchJob(Request $request)
    {
        $search = ($request->get('q')) ? $request->get('q') : '*';
        $jobs = Job::search($search)->orderBy('created_at', 'DESC')->paginate(10);

        SEOMeta::setTitle('Search Job - Uplance');
        SEOMeta::setDescription('Search for a suitable job based on your skills and area of expertise, then apply online.');
        SEOMeta::addMeta('robots', 'noindex', 'property');

        return view('search-job', compact('jobs'));
    }

    public function jobsInSanFrancisco()
    {
        $title = 'Jobs in San Francisco';
        $subtitle = 'List of Jobs available in San Francisco';
        $jobs = Job::where('country', 'US')
            ->where('city', 'San Francisco')->orderBy('created_at', 'DESC')->paginate(40);

        SEOMeta::setTitle('Explore jobs in San Francisco. - Uplance');
        SEOMeta::setDescription("Explore all jobs that clients locate in San Francisco and work with them in good condition");
        SEOTools::setCanonical(route('jobs-in-san-francisco'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Explore jobs in San Francisco. - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription("Explore all jobs that clients locate in San Francisco and work with them in good condition");
        SEOTools::opengraph()->setUrl(route('jobs-in-san-francisco'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Explore jobs in San Francisco. - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription("Explore all jobs that clients locate in San Francisco and work with them in good condition");
        SEOTools::twitter()->setUrl(route('jobs-in-san-francisco'));

        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInNewYork()
    {
        $title = 'Jobs in New York';
        $subtitle = 'List of Jobs available in New York';
        $jobs = Job::where('country', 'US')
            ->where('city', 'New York')->orderBy('created_at', 'DESC')->paginate(40);

        SEOMeta::setTitle('Explore jobs in New York.  - Uplance');
        SEOMeta::setDescription("Explore all jobs that clients locate in New York and work with them in good condition");
        SEOTools::setCanonical(route('jobs-in-new-york'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Explore jobs in New York - Uplance.');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription("Explore all jobs that clients locate in New York and work with them in good condition");
        SEOTools::opengraph()->setUrl(route('jobs-in-new-york'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Explore jobs in New York. - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription("Explore all jobs that clients locate in New York and work with them in good condition");
        SEOTools::twitter()->setUrl(route('jobs-in-new-york'));

        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInLosAngeles()
    {
        $title = 'Jobs in Los Angeles';
        $subtitle = 'List of Jobs available in Los Angeles';
        $jobs = Job::where('country', 'US')
            ->where('city', 'Los Angeles')->orderBy('created_at', 'DESC')->paginate(40);

        SEOMeta::setTitle('Explore jobs in Los Angeles. - Uplance');
        SEOMeta::setDescription("Explore all jobs that clients locate in Los Angeles and work with them in good condition");
        SEOTools::setCanonical(route('jobs-in-los-angeles'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Explore jobs in Los Angeles. - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription("Explore all jobs that clients locate in Los Angeles and work with them in good condition");
        SEOTools::opengraph()->setUrl(route('jobs-in-los-angeles'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Explore jobs in Los Angeles. - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription("Explore all jobs that clients locate in Los Angeles and work with them in good condition");
        SEOTools::twitter()->setUrl(route('jobs-in-los-angeles'));

        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function jobsInMiami()
    {
        $title = 'Jobs in Miami';
        $subtitle = 'List of Jobs available in Miami';
        $jobs = Job::where('country', 'US')
            ->where('city', 'Miami')->orderBy('created_at', 'DESC')->paginate(40);

        SEOMeta::setTitle('Explore jobs in Miami. - Uplance');
        SEOMeta::setDescription("Explore all jobs that clients locate in Miami and work with them in good condition");
        SEOTools::setCanonical(route('jobs-in-los-angeles'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Explore jobs in Miami. - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription("Explore all jobs that clients locate in Miami and work with them in good condition");
        SEOTools::opengraph()->setUrl(route('jobs-in-los-angeles'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Explore jobs in Miami. - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription("Explore all jobs that clients locate in Miami and work with them in good condition");
        SEOTools::twitter()->setUrl(route('jobs-in-los-angeles'));

        return view('jobs-city', compact('title', 'subtitle', 'jobs'));
    }

    public function freelancersInUK()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'GB',
        ])->where('skills', '!=', null)->orderBy('global_rank', 'DESC')->paginate(40);
        $title = 'Freelancers in United Kingdom';
        $subtitle = 'Explore Freelancers in United Kingdom by ranking';

        SEOMeta::setTitle($subtitle . ' - Uplance');
        SEOMeta::setDescription($subtitle);
        SEOTools::setCanonical(route('jobs-in-los-angeles'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle($title . ' - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription($subtitle);
        SEOTools::opengraph()->setUrl(route('jobs-in-los-angeles'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle($title . ' - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription($subtitle);
        SEOTools::twitter()->setUrl(route('jobs-in-los-angeles'));

        return view('freelancers-by-country', compact('freelancers', 'title', 'subtitle'));
    }

    public function freelancersInUSA()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'us',
        ])->where('skills', '!=', null)->orderBy('global_rank', 'DESC')->paginate(40);
        $title = 'Freelancers in USA';
        $subtitle = 'Explore Freelancers in USA by ranking';

        SEOMeta::setTitle($subtitle . ' - Uplance');
        SEOMeta::setDescription($subtitle);
        SEOTools::setCanonical(route('jobs-in-los-angeles'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle($title . ' - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription($subtitle);
        SEOTools::opengraph()->setUrl(route('jobs-in-los-angeles'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle($title . ' - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription($subtitle);
        SEOTools::twitter()->setUrl(route('jobs-in-los-angeles'));

        return view('freelancers-by-country', compact('freelancers', 'title', 'subtitle'));
    }

    public function freelancersInFrance()
    {
        $freelancers = User::where([
            'account_type' => 'freelancer',
            'country' => 'fr',
        ])->where('skills', '!=', null)->orderBy('global_rank', 'DESC')->paginate(40);
        $title = 'Freelancers in France';
        $subtitle = 'Explore Freelancers in France by ranking';

        SEOMeta::setTitle($subtitle . ' - Uplance');
        SEOMeta::setDescription($subtitle);
        SEOTools::setCanonical(route('jobs-in-los-angeles'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle($title . ' - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription($subtitle);
        SEOTools::opengraph()->setUrl(route('jobs-in-los-angeles'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle($title . ' - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription($subtitle);
        SEOTools::twitter()->setUrl(route('jobs-in-los-angeles'));

        return view('freelancers-by-country', compact('freelancers', 'title', 'subtitle'));
    }

    public function about()
    {
        SEOMeta::setTitle('About Us - Uplance');
        SEOMeta::setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::setCanonical(route('about'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('About Us - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::opengraph()->setUrl(route('about'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('About Us - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::twitter()->setUrl(route('about'));

        return view('about');
    }

    public function faq()
    {
        SEOMeta::setTitle('Frequently Asked Questions - Uplance');
        return view('faq');
    }

    public function terms()
    {
        SEOMeta::setTitle('Terms of Service - Uplance');
        return view('terms');
    }
}
