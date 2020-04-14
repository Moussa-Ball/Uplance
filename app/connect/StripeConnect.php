<?php

namespace App\Connect;

use Auth;
use App\WithdrawMethod;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use Swap\Swap;

trait StripeConnect
{
    private function createConnectAccount()
    {
        $user = Auth::user();
        if (!$user->connect_id) {
            if (in_array($user->country, config('stripe_countries'))) {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $account = \Stripe\Account::create([
                    'type' => 'custom',
                    'country' => $user->country,
                    'email' => $user->email,
                    'business_type' => 'individual',
                    'requested_capabilities' => [
                        'card_payments',
                        'transfers',
                    ]
                ]);

                $user->connect_id = $account->id;
                $user->save();
            } else {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'country_not_available' => ['You cannot use this method because of the region where you are.'],
                ]);
                throw $error;
            }
        }
    }

    public function addMethod(Request $request)
    {
        // Check if the token exists.
        $request->validate(['token' => 'required|string']);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create external token.
        $external_account = \Stripe\Account::createExternalAccount($request->user()->connect_id, [
            'external_account' => $request->token,
        ]);

        // Add withdraw method in database.
        $method = WithdrawMethod::create([
            'token' => $request->token,
            'brand' => $external_account->bank_name,
            'type' => $external_account->object,
            'currency' => $external_account->currency,
            'last_four' => $external_account->last4,
            'identifier' => $external_account->id,
            'user_id' => $request->user()->id,
        ]);

        if (WithdrawMethod::where('user_id', $request->user()->id)->count() == 1) {
            $method->default = true;
            $method->save();
        }

        return redirect()->back()->with('success', "Your withdrawal method has been added.");
    }

    public function setDefaultMethod(WithdrawMethod $withdrawMethod)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $external_account = \Stripe\Account::updateExternalAccount($withdrawMethod->user->connect_id, $withdrawMethod->identifier, [
            'default_for_currency' => true
        ]);

        $currency = Countries::where('cca2', Auth::user()->country)->first()->currencies->first();
        if (strtoupper($external_account->currency) == strtoupper($currency) && $external_account->default_for_currency == true) {
            WithdrawMethod::where('user_id', Auth::id())
                ->update(['default' => false]);
            $withdrawMethod->default = true;
            $withdrawMethod->save();
        }

        return redirect()->back()
            ->with('success', "Your withdrawal method has set as default.");
    }

    public function deleteMethod(WithdrawMethod $withdrawMethod)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Account::deleteExternalAccount($withdrawMethod->user->connect_id, $withdrawMethod->identifier);
        $withdrawMethod->delete();
        return redirect()->back()->with('success', "Your withdrawal method has been deleted.");
    }

    public function getExternelAccount(string $id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $externel_account = \Stripe\Account::retrieveExternalAccount(Auth::user()->connect_id, $id);
        return $externel_account;
    }

    public function transfer($amount, $destination)
    {
        $currency = Countries::where('cca2', Auth::user()->country)->first()->currencies->first();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $amount = $rate = Swap::latest("USD/{$currency}");;
        return \Stripe\Transfer::create([
            'amount' => $amount * 100,
            'currency' => $currency,
            'destination' => $destination,
        ]);
    }

    public function connectAccount()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        return \Stripe\Account::retrieve(Auth::user()->connect_id);
    }

    public function checkIdentity()
    {
        $this->createConnectAccount();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $account_link = \Stripe\AccountLink::create([
            'account' => Auth::user()->connect_id,
            'failure_url' => route('identity.failure'),
            'success_url' =>  route('identity.success'),
            'type' => 'custom_account_verification',
        ]);
        return $account_link->url;
    }
}
