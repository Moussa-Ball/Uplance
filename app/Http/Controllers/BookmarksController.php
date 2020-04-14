<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Job;
use App\User;
use Book;
use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function index(Request $request)
    {
        $jobs = [];
        $users = [];

        // Get job s bookmarked.
        $bookmarkJobs = Bookmark::where([
            'bookmark_type' => 'App\Job',
            'user_id' => $request->user()->id
        ])->get();

        foreach ($bookmarkJobs as $value) {
            $job = Job::where('id', $value->bookmark_id)->first();
            if ($job) $jobs[] = [$job, $value];
        }

        // Get users bookmarked.
        $bookmarkFreelancers = Bookmark::where([
            'bookmark_type' => 'App\User',
            'user_id' => $request->user()->id
        ])->get();

        foreach ($bookmarkFreelancers as $value) {
            $user = User::where('id', $value->bookmark_id)->first();
            if ($user) $users[] = [$user, $value];
        }

        $bookmarkJobs = $jobs;
        $bookmarkFreelancers = $users;

        return view('bookmarks', compact('bookmarkJobs', 'bookmarkFreelancers'));
    }

    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();
        return redirect()->back()->with('success', 'Your bookmark has been deleted.');
    }
}
