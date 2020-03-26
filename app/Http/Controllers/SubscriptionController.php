<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $subscribed_to_pro = $user->subscribed('pro');
        $subscribed_to_business = $user->subscribed('business');
        return view('plans.index', compact('user', 'subscribed_to_pro', 'subscribed_to_business'));
    }

    public function subscribeToPro(Request $request)
    {
        $paymentMethod = $request->user()->defaultPaymentMethod();
        if ($paymentMethod) {
            try {
                $request->user()->newSubscription('pro', 'plan_GyWPNQsPXWAwqm')->create($paymentMethod->id, [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ]);
                return response()->json('The premium pro plan has been activated! You can now start taking advantage of this plan.');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        } else {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'payment_method' => ['You have not yet added a payment method.'],
            ]);
            throw $error;
        }
    }

    public function subscribeToBusiness(Request $request)
    {
        $paymentMethod = $request->user()->defaultPaymentMethod();
        if ($paymentMethod) {
            try {
                $request->user()->newSubscription('business', 'plan_GyWQ8tjO0p9hn5')->create($paymentMethod->id, [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ]);
                return response()->json('The premium business plan has been activated! You can now start taking advantage of this plan.');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        } else {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'payment_method' => ['You have not yet added a payment method.'],
            ]);
            throw $error;
        }
    }
}
