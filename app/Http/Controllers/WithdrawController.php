<?php

namespace App\Http\Controllers;

use Auth;
use App\Withdraw;
use App\WithdrawMethod;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
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
        $methods = WithdrawMethod::where('user_id', $request->user()->id)->get();
        foreach ($withdraws as $key => $withdraw) {
            if ($withdraw->withdraw_date <= \Carbon\Carbon::now()) {
                $balance += $withdraw->amount;
            }
        }
        $intent = $request->user()->createSetupIntent();
        $currency = Countries::where('cca2', $request->user()->country)->first()->currencies->first();
        return view('payments.paid', compact('balance', 'methods', 'currency'));
    }

    /**
     * Add a withdraw method.
     *
     */
    public function addMethod(Request $request)
    {
        if (!$request->user()->connect_id)
            return redirect()->back()
                ->with('error', "You must verify your identity and add a payment method to withdraw money.");
        return $request->user()->addMethod($request);
    }

    /**
     * Set withdraw method as default.
     */
    public function setDefault(Request $request, WithdrawMethod $withdrawMethod)
    {
        $currency = Countries::where('cca2', Auth::user()->country)->first()->currencies->first();
        if ($withdrawMethod->default || strtoupper($withdrawMethod->currency) != strtoupper($currency))
            return $this->authorize(false);
        return $request->user()->setDefaultMethod($withdrawMethod);
    }

    /**
     * Allows to remove a method.
     */
    public function remove(Request $request, WithdrawMethod $withdrawMethod)
    {
        if ($withdrawMethod->default)
            return $this->authorize(false);
        return $request->user()->deleteMethod($withdrawMethod);
    }

    /**
     * Allows withdraw payment.
     */
    public function getPaid(Request $request)
    {
        $balance = 0;
        $withdraws = Withdraw::where('user_id', $request->user()->id)->get();
        foreach ($withdraws as $key => $withdraw) {
            if ($withdraw->withdraw_date <= \Carbon\Carbon::now())
                $balance += $withdraw->amount;
        }

        if (!$balance)
            return $this->authorize(false);

        if (!$request->user()->connect_id)
            return redirect()
                ->back()
                ->with('error', "You must verify your identity and add a payment method to withdraw money.");

        if (WithdrawMethod::where('user_id', $request->user()->id)->get()->isEmpty())
            return redirect()
                ->back()
                ->with('error', "You must first add a withdrawal method.");

        // Send money to freelancer.
        $request->user()->transfer($balance, $request->user()->connect_id);

        foreach ($withdraws as $key => $withdraw) {
            if ($withdraw->withdraw_date <= \Carbon\Carbon::now())
                $withdraw->delete();
        }
        return redirect()->back()->with('success', "Your fund is being transferred.");
    }
}
