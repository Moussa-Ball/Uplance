<?php

namespace App\Http\Controllers;

use Auth;
use App\Job;
use App\ProfileView;
use App\Review;
use App\Proposal;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderBy('created_at', 'DESC')->paginate(10);
        $proposals = Proposal::where('user_id', Auth::id())->count();
        $jobs_published = Job::where('user_id', Auth::id())->count();
        $reviews = Review::where('to_id', Auth::id())->count();

        // Get data.
        $views = ProfileView::where('user_id', Auth::id())->whereYear('created_at', Carbon::now()->year)->get()->groupBy(function ($item) {
            return $item->created_at->format('F');
        })->toArray();

        // Parse data.
        $data = [];
        $items = array_keys($views);
        foreach ($items as $key => $item) {
            $data[$item] = count($views[$item]);
        }

        SEOMeta::setTitle('My Dashboard');
        return view('dashboard', compact('proposals', 'jobs_published', 'reviews', 'notifications', 'data'));
    }
}
