<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Exception;
use App\Invoice;
use App\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\MessageBag;
use Artesaos\SEOTools\Facades\SEOMeta;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        SEOMeta::setTitle('Billing Method');
        return view('payments.method', [
            'intent' => $user->createSetupIntent(),
            'payment_methods' => $request->user()->paymentMethods(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $payment_method = $request->input('payment_method');
        $user = Cashier::findBillable($request->user()->stripe_id);

        if ($user == null) {
            $request->user()->createAsStripeCustomer();
        }

        $request->user()->addPaymentMethod($payment_method);
        if (!$request->user()->hasPaymentMethod()) {
            $request->user()->updateDefaultPaymentMethod($payment_method);
        }
        return redirect()->back()->with('success', 'Your payment method has been added.');
    }

    public function enableDefaultMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $payment_method = $request->input('payment_method');
        $request->user()->updateDefaultPaymentMethod($payment_method);
        return redirect()->back()->with('success', 'Your default payment method has been changed.');
    }

    public function removePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $payment_method = $request->input('payment_method');
        $payment_method = $request->user()->findPaymentMethod($payment_method);

        if ($payment_method) {
            $payment_method->delete();
            return redirect()->back()->with('success', 'The payment method has been deleted.');
        } else {
            return abort(404);
        }
    }

    /**
     * Calculate the budget of a freelancer taking into account the service fee.
     */
    private function calculateFees($amount)
    {
        if ($amount >= 5 && $amount <= 10) {
            $fee = $amount / 100 * 20;
            return $amount - $fee;
        }

        if ($amount >= 10 && $amount <= 20) {
            $fee = $amount / 100 * 10;
            return $amount - $fee;
        }

        if ($amount >= 20) {
            $fee = $amount / 100 * 5;
            return $amount - $fee;
        }
    }

    /**
     * Calculate the service fee.
     */
    private function getFees($amount)
    {
        if ($amount >= 5 && $amount <= 10) {
            return $amount / 100 * 20;
        }

        if ($amount >= 10 && $amount <= 20) {
            return $amount / 100 * 10;
        }

        if ($amount >= 20) {
            return $amount / 100 * 5;
        }
    }

    public function finishPayment(Request $request, Invoice $invoice)
    {
        $this->authorize('owner', $invoice->from->id);
        $paymentMethod = $request->user()->defaultPaymentMethod();

        if ($paymentMethod) {
            try {
                $amount = $invoice->amount * 100;
                $payment = $request->user()->charge($amount, $paymentMethod->id);

                if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                    $user = User::where('id', $invoice->to_id)->first();
                    $user->total_earning += $this->calculateFees($invoice->amount); //to changed
                    $user->save();

                    $current_user = $request->user();
                    $current_user->spent += $invoice->amount;
                    $current_user->save();

                    /**
                     * ------------------------------------------------------------------------
                     * When it is Fixed Price without milestones.
                     * ------------------------------------------------------------------------
                     */
                    if ($invoice->type == 'Fixed Price' && !$invoice->contract->milestones) {
                        Contract::where('id', $invoice->contract_id)->update([
                            'project_paid' => $invoice->amount,
                            'total_earnings' => $invoice->amount,
                            'completed' => true,
                        ]);
                        $invoice->total_due = $this->calculateFees($invoice->amount);
                        $invoice->service_fee = $this->getFees($invoice->amount);
                        $invoice->total = $invoice->amount;
                        $invoice->paid_at = Carbon::now();
                        $invoice->save();

                        //TODO: Review & Notification.

                        return response()->json(route('invoices.success', $invoice->hashid));
                    }

                    /**
                     * ------------------------------------------------------------------------
                     * When it is Fixed Price with milestones.
                     * ------------------------------------------------------------------------
                     */
                    elseif ($invoice->type == 'Fixed Price' && $invoice->contract->milestones) {
                        $contract = Contract::where('id', $invoice->contract_id)->first();
                        $contract->milestones_paid += $invoice->amount;
                        $contract->total_earnings += $invoice->amount;
                        $contract->remaining -= $invoice->amount;
                        $contract->completed = ($contract->remaining <= 0) ? true : false;
                        $contract->save();

                        $invoice->total_due = $this->calculateFees($invoice->amount);
                        $invoice->service_fee = $this->getFees($invoice->amount);
                        $invoice->total = $invoice->amount;
                        $invoice->paid_at = Carbon::now();
                        $invoice->save();

                        if ($contract->remaining <= 0) {
                            //TODO: Review & Notification.
                            return response()->json('Your payment was successful. The contract is now closed.');
                        } else {
                            return response()->json('The milestone payment was successful.');
                        }
                    }

                    /**
                     * ------------------------------------------------------------------------
                     * When it is Hourly Rate.
                     * ------------------------------------------------------------------------
                     */
                    else {
                        $contract = $invoice->contract;
                        $contract->total_earnings += $invoice->amount;
                    }
                }
            } catch (Exception $e) {
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
