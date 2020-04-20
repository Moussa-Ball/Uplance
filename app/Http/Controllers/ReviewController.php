<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Review;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function index(Request $request)
    {
        $reviews = Review::where('from_id', $request->user()->id)->paginate(10);
        SEOMeta::setTitle('Reviews');
        return view('reviews.index', compact('reviews'));
    }

    public function leave(Review $review)
    {
        if ($review->from_id != Auth::id() || $review->rated == true)
            return $this->authorize(false);
        SEOMeta::setTitle('Leave a review');
        return view('reviews.leave', compact('review'));
    }

    public function storeReview(Request $request, Review $review)
    {
        if ($request->user()->id == $review->to->id) {
            if ($request->validate([
                'rating' => 'required|numeric|min:1|max:5',
                'ontime' => 'required',
                'comment' => 'required',
                'onbudget' => 'required',
            ])) {
                $review->update([
                    'rated' => true,
                    'rating' => (int) $request->input('rating'),
                    'comment' => $request->input('comment'),
                    'on_time' => ($request->input('ontime') === 'yes') ? true : false,
                    'on_budget' => ($request->input('onbudget') === 'yes') ? true : false,
                ]);
                $user = User::where('id', $review->to_id)->first();

                if ($review->on_time) {
                    if ($user->on_time < 100) {
                        $user->on_time += 10;
                        $user->save();
                    }
                } else {
                    if ($user->on_time > 10) {
                        $user->on_time -= 10;
                        $user->save();
                    }
                }

                if ($review->on_budget) {
                    if ($user->on_budget < 100) {
                        $user->on_budget += 10;
                        $user->save();
                    }
                } else {
                    if ($user->on_budget > 10) {
                        $user->on_budget -= 10;
                        $user->save();
                    }
                }

                if ($review->on_time && $review->on_budget) {
                    if ($request->rating == 5 || $request->rating == 4) {
                        $user->job_succes += 10;
                    } else {
                        $user->job_succes += 5;
                    }
                    $user->save();
                } else {
                    if ($user->job_succes >= 10) {
                        $user->job_succes -= 10;
                    }
                    $user->save();
                }

                $recommendation = ($user->job_succes + $user->on_time + $user->on_budget) / 3;

                if ($recommendation) {
                    $user->recommendation = $recommendation;
                    $user->rating = (ceil($recommendation / 20) > 100) ? 100 : ceil($recommendation / 20);
                    $user->save();
                }

                $rtg = $user->job_succes + $user->on_time + $user->on_budget + $user->recommendation + $user->rehired + $user->jobs_done;

                $user->global_rank = $rtg;
                $user->save();
            }
            return response()->json(['message' => 'Your review has been sent.']);
        }

        if ($request->user()->id == $review->from->id) {
            if ($request->validate([
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'required',
            ])) {
                $review->update([
                    'rated' => true,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);
                return response()->json(['message' => 'Your review has been sent.']);
            }
        }
    }
}
