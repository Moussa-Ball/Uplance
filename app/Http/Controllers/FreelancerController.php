<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Review;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use PragmaRX\Countries\Package\Countries;

class FreelancerController extends Controller
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
        SEOMeta::setTitle('Find Freelancer');
        return view('freelancers.index');
    }

    /**
     * Search a freelancer.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $keywords = ($request->get('q')) ? $request->get('q') : '*';
        $location = $request->get('location');
        $category =  $request->get('category');
        $hourly_rate =  ($request->get('hourly_rate') == '0') ? 150 : $request->get('hourly_rate');
        $skills =  $request->get('skills');
        $sort_by =  $request->get('sort_by');

        $user = new User();
        $user = $user->search($keywords);
        $user = $user->where('current_account', 'freelancer');
        $user = $user->where('skills', '!=', 'NULL');

        if ($location) {
            $country = new Countries();
            $country = $country->whereNameCommon($location)->first()->cca2;
            $user = $user->where('country', strtoupper($country));
        }

        if ($category) {
            $category = Category::where('name', 'LIKE', '%' . $category . '%')->first();
            $user = $user->where('category_id', $category->id);
        }

        if ($hourly_rate > 150) {
            $user = $user->where('hourly_rate', '=', 150);
        } else {
            $user = $user->where('hourly_rate', '<=', $hourly_rate);
        }

        if ($skills && $skills !== "undefined") {
            $user = $user->whereIn('skills', explode(' ', strtolower($skills)));
        }

        if ($sort_by == 'Newest') {
            $user = $user->orderBy('created_at', 'DESC');
        } elseif ($sort_by == 'Oldest') {
            $user = $user->orderBy('created_at', 'ASC');
        } elseif ($sort_by == 'Relevance') {
            $user = $user->orderBy('global_rank', 'DESC');
        }

        return $user->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $freelancer = $user;
        $reviews = Review::where('to_id', $user->id)->where('rated', true)->paginate(10);

        SEOMeta::setTitle($user->name . ' - ' . $user->tagline);
        SEOMeta::setDescription($user->presentation);
        SEOTools::setCanonical(route('freelancers.show', $user->hashid));
        SEOMeta::addMeta('robots', 'noarchive', 'property');

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle($user->name . ' - ' . $user->tagline);
        SEOTools::opengraph()->addImage($user->avatar);
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription($user->presentation);
        SEOTools::opengraph()->setUrl(route('freelancers.show', $user->hashid));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle($user->name . ' - ' . $user->tagline);
        SEOTools::twitter()->setImages($user->avatar);
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription($user->presentation);
        SEOTools::twitter()->setUrl(route('freelancers.show', $user->hashid));

        return view('freelancers.show', compact('freelancer', 'reviews'));
    }
}
