<?php

namespace App\Http\Controllers;

use App\Withdraw;
use App\WithdrawMethod;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;

class WithdrawController extends Controller
{
    /**
     * Add password confirmation before any action.
     */
    public function __construct()
    {
        $this->middleware('password.confirm');
    }

    /**
     * Display the withdraw page.
     *
     * @return \Illuminate\Http\View
     */
    public function index(Request $request)
    {
        $balance = 0;
        $withdraws = Withdraw::where('user_id', $request->user()->id)->get();
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();
        foreach ($withdraws as $key => $withdraw) {
            if ($withdraw->withdraw_date <= \Carbon\Carbon::now()) {
                $balance += $withdraw->amount;
            }
        }
        return view('payments.paid', compact('balance', 'methods'));
    }

    /**
     * Send money to an user from paypal.
     */
    private function getPaidWithPaypal(Request $request, $balance)
    {
        $paypalConf = config('paypal');
        $apiContext = new ApiContext(new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET')));
        $apiContext->setConfig($paypalConf);

        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();

        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("You have withdrawn your earnings on uplance.");


        $sender_item_id = uniqid();

        $senderItem = new \PayPal\Api\PayoutItem([
            "recipient_type" => "EMAIL",
            "receiver" => 'moiseball20155-buyer@gmail.com', //$request->user()->email,
            "note" => "Thank you.",
            "sender_item_id" => $sender_item_id,
            "amount" => array(
                "value" => $balance,
                "currency" => "USD"
            )
        ]);

        $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

        try {
            $output = $payouts->create(null, $apiContext);

            if ($output->getBatchHeader()->getBatchStatus() == 'PENDING') {
                return redirect()->back()->with('success', 'The transfer of funds to your paypal account is being processed.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while transferring your funds to your account. Please try later.');
            }
        } catch (PayPalConnectionException $e) {
            return redirect()->back()->with('error', 'An error occurred while transferring your funds to your account. Please try later.');
        }
    }

    /**
     * Send money to an user from stripe connect.
     */
    private function getPaidWithCreditCard(Request $request, $balance)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        /*$account = \Stripe\Account::create([
            'type' => 'custom',
            'country' => 'US',
            'first_name' => $request->user()->first_name,
            'last_name' => $request->user()->last_name,
            'email' => $request->user()->email,
            'business_type' => 'individual',
            'requested_capabilities' => [
                'card_payments',
                'transfers',
            ],
        ]);
        dd($account);*/

        $balance = \Stripe\Balance::retrieve(['stripe_account' => $request->user()->stripe_id]);
        dd($balance);
    }

    /**
     * Allows to withdraw balance from default withdrawal method.
     *
     */
    public function getPaid(Request $request)
    {
        $balance = 0;
        $withdraws = Withdraw::where('user_id', $request->user()->id)->get();
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();

        if (!$methods || $methods && !$methods->default_method) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'withdraw_method' => ['No withdrawal method has yet been added.'],
            ]);
            throw $error;
        }

        foreach ($withdraws as $key => $withdraw) {
            if ($withdraw->withdraw_date <= \Carbon\Carbon::now()) {
                $balance += $withdraw->amount;
            }
        }

        if ($balance) {
            if ($methods && $methods->default_method && $methods->default_method == 'paypal') {
                return $this->getPaidWithPaypal($request, $balance);
            }

            if ($methods && $methods->default_method && $methods->default_method == 'credit_card') {
                return $this->getPaidWithCreditCard($request, $balance);
            }
        } else {
            return $this->authorize(true);
        }
    }

    public function activatePaypalMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();
        if ($methods) {
            if ($methods->paypal)
                return $this->authorize('true');

            $methods->paypal = true;
            $methods->paypal_activation_date = \Carbon\Carbon::now()->addDay(2);
            $methods->save();
        } else {
            WithdrawMethod::create([
                'paypal' => true,
                'default_method' => 'paypal',
                'user_id' => $request->user()->id
            ]);
        }
        return redirect()->back()
            ->with('success', 'Your paypal withdrawal method is being activated. Please wait 2 few days.');
    }

    public function activateCreditCardMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();
        if ($methods) {
            if ($methods->credit_card)
                return $this->authorize('true');
            $methods->credit_card = true;
            $methods->credit_card_activation_date = \Carbon\Carbon::now()->addDay(2);
            $methods->save();
        } else {
            WithdrawMethod::create([
                'credit_card' => true,
                'default_method' => 'credit_card',
                'user_id' => $request->user()->id
            ]);
        }
        return redirect()->back()
            ->with('success', 'Your credit card withdrawal method is being activated. Please wait 2 few days.');
    }

    public function makePaypalDefaultMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();
        if ($methods) {
            $methods->default_method = 'paypal';
            $methods->save();
        } else {
            return $this->authorize('true');
        }
        return redirect()->back()
            ->with('success', 'Your default withdrawal method is now set to paypal.');
    }

    public function makeCreditCardDefaultMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();
        if ($methods) {
            $methods->default_method = 'credit_card';
            $methods->save();
        } else {
            return $this->authorize('true');
        }
        return redirect()->back()
            ->with('success', 'Your default withdrawal method is now set to credit card.');
    }

    public function removePaypalMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();

        if ($methods) {
            if ($methods->default_method = 'paypal') {
                $methods->default_method = null;
            }
            $methods->paypal = false;
            $methods->paypal_activation_date = null;
            $methods->save();
        } else {
            return $this->authorize('true');
        }

        return redirect()->back()
            ->with('success', 'Your paypal withdrawal method has been deleted.');
    }

    public function removeCreditCardMethod(Request $request)
    {
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->first();

        if ($methods) {
            if ($methods->default_method = 'credit_card') {
                $methods->default_method = null;
            }
            $methods->credit_card = false;
            $methods->credit_card_activation_date = null;
            $methods->save();
        } else {
            return $this->authorize('true');
        }

        return redirect()->back()
            ->with('success', 'Your credit card withdrawal method has been deleted.');
    }
}
