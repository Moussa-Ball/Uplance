<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view(
            'plans.index',
            [
                'user' => $user,
                'grace_period_in_pro' => ($user->subscription('Pro') && $user->subscription('Pro')->onGracePeriod()) ? true : false,
                'grace_period_in_business' => ($user->subscription('business') && $user->subscription('business')->onGracePeriod()) ? true : false,
                'subscribed_to_pro' => $user->subscribed('pro'),
                'subscribed_to_business' => $user->subscribed('business')
            ]
        );
    }

    public function subscribeToPro(Request $request)
    {
        $paymentMethod = $request->user()->defaultPaymentMethod();
        if ($paymentMethod) {
            try {
                $request->user()->newSubscription('pro', 'plan_H0TcmNj1SGJ3zr')->create($paymentMethod->id, [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ]);
                return redirect()->back()
                    ->with('success', 'The premium pro plan has been activated! You can now start taking advantage of this plan.');
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
                $request->user()->newSubscription('business', 'plan_H0Td1KJRPVeaNI')->create($paymentMethod->id, [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ]);
                return redirect()->back()
                    ->with('success', 'The premium business plan has been activated! You can now start taking advantage of this plan.');
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

    public function cancelProSubscription(Request $request)
    {
        if ($request->user()->subscribed('pro')) {
            $request->user()->subscription('pro')->cancel();
            return redirect()->back()->with('success', 'Your subscription has been suspended.');
        } else {
            return $this->authorize(true);
        }
    }

    public function cancelBusinessSubscription(Request $request)
    {
        if ($request->user()->subscribed('business')) {
            $request->user()->subscription('business')->cancel();
            return redirect()->back()->with('success', 'Your subscription has been suspended.');
        } else {
            return $this->authorize(true);
        }
    }

    public function resumeProSubscription(Request $request)
    {
        if ($request->user()->subscribed('pro')) {
            $request->user()->subscription('pro')->resume();
            return redirect()->back()->with('success', 'Your subscription is restored to the active state.');
        } else {
            return $this->authorize(true);
        }
    }

    public function resumeBusinessSubscription(Request $request)
    {
        if ($request->user()->subscribed('business')) {
            $request->user()->subscription('business')->resume();
            return redirect()->back()->with('success', 'Your subscription is restored to the active state.');
        } else {
            return $this->authorize(true);
        }
    }

    public function switchProSubscriptionToBusiness(Request $request)
    {
        if ($request->user()->subscription('pro')) {
            try {
                $request->user()->subscription('pro')->swap('plan_H0Td1KJRPVeaNI');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'We encountered an error while modifying your subscription.');
            }
            \DB::table('subscriptions')->where('user_id', $request->user()->id)->update([
                'name' => 'business'
            ]);
            return redirect()->back()->with('success', 'Your subscription is swapped to Business.');
        } else {
            return $this->authorize(true);
        }
    }

    public function switchBusinessSubscriptionToPro(Request $request)
    {
        if ($request->user()->subscription('business')) {
            try {
                $request->user()->subscription('business')->swap('plan_H0TcmNj1SGJ3zr');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'We encountered an error while modifying your subscription.');
            }
            \DB::table('subscriptions')->where('user_id', $request->user()->id)->update([
                'name' => 'pro'
            ]);
            return redirect()->back()->with('success', 'Your subscription is swapped to Pro.');
        } else {
            return $this->authorize(true);
        }
    }
}
