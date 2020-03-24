<?php

namespace App\Http\Controllers;

use Auth;
use App\Job;
use App\Review;
use App\Proposal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderBy('created_at', 'DESC')->paginate(10);
        $proposals = Proposal::where('user_id', Auth::id())->count();
        $jobs_published = Job::where('user_id', Auth::id())->count();
        $reviews = Review::where('to_id', Auth::id())->orWhere('from_id', Auth::id())->get();
        return view('dashboard', compact('proposals', 'jobs_published', 'reviews', 'notifications'));
    }
}
