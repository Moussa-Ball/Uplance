<?php

namespace App\Http\Controllers;

use App\Notifications\CreditPurchased;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        return view('credits.index');
    }

    public function buy(Request $request)
    {
        $this->authorize('freelancer');
        $request->validate(['option' => 'required|int']);
        $paymentMethod = $request->user()->defaultPaymentMethod();

        if ($paymentMethod) {
            $credit = $request->option;
            $user = $request->user();

            switch ($credit) {
                case 1:
                    try {
                        $payment = $user->charge($credit * 100, $paymentMethod->id);

                        if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                            $user->credit += 10;
                            $user->save();
                            $user->notify(new CreditPurchased($user, 10, 1));
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 422);
                    }
                    break;
                case 2:
                    try {
                        $payment = $user->charge($credit * 100, $paymentMethod->id);

                        if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                            $user->credit += 20;
                            $user->save();
                            $user->notify(new CreditPurchased($user, 20, 2));
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 422);
                    }
                    break;
                case 3:
                    try {
                        $payment = $user->charge($credit * 100, $paymentMethod->id);

                        if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                            $user->credit += 30;
                            $user->save();
                            $user->notify(new CreditPurchased($user, 30, 3));
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 422);
                    }
                    break;
                case 4:
                    try {
                        $payment = $user->charge(10 * 100, $paymentMethod->id);

                        if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                            $user->credit += 100;
                            $user->save();
                            $user->notify(new CreditPurchased($user, 100, 10));
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 422);
                    }
                    break;
                default:
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'credit_option' => ['You have not selected a good option.'],
                    ]);
                    throw $error;
                    break;
            }

            return redirect()->back()->with('success', "You have successfully purchased credit.");
        } else {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'payment_method' => ['You have not yet added a payment method.'],
            ]);
            throw $error;
        }
    }
}
