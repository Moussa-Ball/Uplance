<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Review;
use Illuminate\Http\Request;
use App\Notifications\ContractEnd;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('from_id', Auth::id())->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function leave($id)
    {
        $review = Review::where('id', $id)->first();
        if (!$review) {
            return abort(404);
        }
        if ($review->from_id != Auth::id()) {
            return $this->authorize(false);
        }
        if ($review->rated == true) {
            return $this->authorize(false);
        }
        return view('reviews.leave', compact('review'));
    }

    public function storeReview(Request $request, $id)
    {
        if ($request->validate([
            'rating' => 'required',
            'ontime' => 'required',
            'comment' => 'required',
            'onbudget' => 'required',
        ])) {
            Review::where('id', $id)->update([
                'rated' => true,
                'rating' => (int) $request->input('rating'),
                'comment' => $request->input('comment'),
                'on_time' => ($request->input('ontime') === 'yes') ? true : false,
                'on_budget' => ($request->input('onbudget') === 'yes') ? true : false,
            ]);

            $review = Review::where('id', $id)->first();
            $user = User::where('id', $review->to_id)->first();

            $positif = Review::where('rating', '>=', 3)->count();
            $negatif = Review::where('rating', '<=', 2)->count();
            $job_succes = $positif - $negatif * 100;

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

            $recommendation = ($user->job_success + $user->on_time + $user->on_budget) / 3;

            if ($recommendation) {
                $user->recommendation = $recommendation;
                $user->rating = (ceil($recommendation / 20) > 100) ? 100 : ceil($recommendation / 20);
                $user->save();
            }

            $rtg = $user->job_success + $user->on_time + $user->on_budget + $user->recommendation + $user->rehired + $user->jobs_done;

            $user->global_rank = $rtg;
            $user->save();
        }
        return response()->json(['message' => 'Your review has been sent.']);
    }
}
