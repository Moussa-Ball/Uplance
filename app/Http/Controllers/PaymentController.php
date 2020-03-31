<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Exception;
use App\Invoice;
use App\Contract;
use App\Notifications\ContractEnd;
use App\Notifications\PaymentMade;
use App\Notifications\PaymentReceived;
use App\Review;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Artesaos\SEOTools\Facades\SEOMeta;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Cashier::findBillable($request->user()->stripe_id);
        if ($user == null) {
            $request->user()->createAsStripeCustomer();
        }
        SEOMeta::setTitle('Billing Method');
        return view('payments.method', [
            'intent' => $request->user()->createSetupIntent(),
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

                Withdraw::create([
                    'amount' => $invoice->amount,
                    'user_id' => $invoice->to_id,
                ]);

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
                        $invoice->from->notify(new PaymentMade($invoice));
                        $invoice->to->notify(new PaymentReceived($invoice));
                        $invoice->to->notify(new ContractEnd($invoice->contract, 'freelancer'));
                        $invoice->from->notify(new ContractEnd($invoice->contract, 'client'));

                        Review::create([
                            'rating' => 0,
                            'rated' => false,
                            'on_time' => false,
                            'on_budget' => false,
                            'to_id' => $invoice->to_id,
                            'from_id' => $invoice->from_id,
                            'contract_id' => $invoice->contract_id,
                        ]);

                        Review::create([
                            'rating' => 0,
                            'rated' => false,
                            'on_time' => false,
                            'on_budget' => false,
                            'to_id' => $invoice->from_id,
                            'from_id' => $invoice->to_id,
                            'contract_id' => $invoice->contract_id,
                        ]);

                        $invoice->contract->to->jobs_done += 1;
                        $invoice->contract->to->save();

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

                        // Notifications
                        $invoice->from->notify(new PaymentMade($invoice));
                        $invoice->to->notify(new PaymentReceived($invoice));

                        if ($contract->remaining <= 0) {
                            // Notifications
                            $invoice->to->notify(new ContractEnd($invoice->contract, 'freelancer'));
                            $invoice->from->notify(new ContractEnd($invoice->contract, 'client'));

                            // Reviews
                            Review::create([
                                'rating' => 0,
                                'rated' => false,
                                'on_time' => false,
                                'on_budget' => false,
                                'to_id' => $invoice->to_id,
                                'from_id' => $invoice->from_id,
                                'contract_id' => $invoice->contract_id,
                            ]);

                            Review::create([
                                'rating' => 0,
                                'rated' => false,
                                'on_time' => false,
                                'on_budget' => false,
                                'to_id' => $invoice->from_id,
                                'from_id' => $invoice->to_id,
                                'contract_id' => $invoice->contract_id,
                            ]);

                            $contract->to->jobs_done += 1;
                            $contract->to->save();
                        }
                        return response()->json(route('invoices.success', $invoice->hashid));
                    }
                }
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

    private function generateOrder($index = 0)
    {
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
        return $unique = $today . $rand . $index;
    }

    private function hourlyRatePayment(Request $request, Invoice $invoice, $end)
    {
        $this->authorize('owner', $invoice->from->id);
        $paymentMethod = $request->user()->defaultPaymentMethod();

        if ($paymentMethod) {
            try {
                $amount = $invoice->amount * 100;
                $payment = $request->user()->charge($amount, $paymentMethod->id);

                Withdraw::create([
                    'amount' => $invoice->amount,
                    'user_id' => $invoice->to_id,
                ]);

                if ($payment->charges->data[0]['paid'] == true && $payment->charges->data[0]['status'] == 'succeeded') {
                    $user = $invoice->to;
                    $user->total_earning = $user->total_earning + $this->calculateFees($invoice->amount);
                    $user->save();

                    $current_user = $request->user();
                    $current_user->spent += $invoice->amount;
                    $current_user->save();

                    $contract = $invoice->contract;
                    $contract->total_earnings += $invoice->amount;
                    $contract->save();

                    if ($end) {
                        // End contract.
                        $contract = $invoice->contract;
                        $contract->completed = true;
                        $contract->save();

                        $invoice->hours = $contract->work_hours;
                        $contract->save();

                        $contract->to->jobs_done += 1;
                        $contract->to->save();
                    } else {
                        $invoice->hours = $contract->work_hours;
                        $contract->total_hours += $contract->work_hours;
                        $contract->work_hours = 0;
                        $contract->save();

                        Invoice::create([
                            'order' => $this->generateOrder(),
                            'issued' => Carbon::now(),
                            'description' => $invoice->description,
                            'type' => 'Hourly Rate',
                            'rate' => $invoice->rate,
                            'contract_id' => $invoice->contract_id,
                            'to_id' => $invoice->to_id,
                            'from_id' => $invoice->from_id,
                        ]);
                    }

                    $invoice->total_due = $this->calculateFees($invoice->amount);
                    $invoice->service_fee = $this->getFees($invoice->amount);
                    $invoice->total = $invoice->amount;
                    $invoice->paid_at = Carbon::now();
                    $invoice->save();

                    // Notifications
                    $invoice->from->notify(new PaymentMade($invoice));
                    $invoice->to->notify(new PaymentReceived($invoice));

                    if ($end) {
                        $invoice->to->notify(new ContractEnd($invoice->contract, 'freelancer'));
                        $invoice->from->notify(new ContractEnd($invoice->contract, 'client'));

                        // TODO: Review Here.
                        Review::create([
                            'rating' => 0,
                            'rated' => false,
                            'on_time' => false,
                            'on_budget' => false,
                            'to_id' => $invoice->to_id,
                            'from_id' => $invoice->from_id,
                            'contract_id' => $invoice->contract_id,
                        ]);

                        Review::create([
                            'rating' => 0,
                            'rated' => false,
                            'on_time' => false,
                            'on_budget' => false,
                            'to_id' => $invoice->from_id,
                            'from_id' => $invoice->to_id,
                            'contract_id' => $invoice->contract_id,
                        ]);
                    }
                    return response()->json(route('invoices.success', $invoice->hashid));
                }
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

    public function paidAndContinueContract(Request $request, Invoice $invoice)
    {
        //Check authorization.
        $this->authorize('owner', $invoice->from->id);

        // Set invoice amount.
        $invoice->amount = $invoice->contract->rate * $invoice->contract->work_hours;
        $invoice->save();

        // Do the payment.
        return $this->hourlyRatePayment($request, $invoice, false);
    }

    public function paidAndEndContract(Request $request, Invoice $invoice)
    {
        //Check authorization.
        $this->authorize('owner', $invoice->from->id);

        // Set invoice amount.
        $invoice->amount = $invoice->contract->rate * $invoice->contract->work_hours;
        $invoice->save();

        // Do the payment.
        return $this->hourlyRatePayment($request, $invoice, true);
    }
}
