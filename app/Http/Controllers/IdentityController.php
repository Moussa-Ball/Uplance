<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IdentityController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function index()
    {
        if (\Auth::user()->current_account != 'freelancer')
            return $this->authorize(false);

        SEOMeta::setTitle('Your Identity');
        return view('identities.index');
    }

    public function checkStripeIdentity(Request $request)
    {
        if (Auth::user()->connect_verified)
            return $this->authorize(false);
        return Redirect::to($request->user()->checkIdentity());
    }

    public function success(Request $request)
    {
        if ($request->user()->connect_verified)
            return $this->authorize(false);

        if (
            $request->user()->connectAccount()->capabilities->card_payments == 'inactive' ||
            $request->user()->connectAccount()->capabilities->platform_payments == 'inactive'
        ) {
            return redirect()->route('identity.index')
                ->with('warning', "Your identity could no be verified.");
        } else {
            $request->user()->update(['connect_verified' => 1]);
            return redirect()->route('identity.index')
                ->with('success', "Your identity has been verified.");
        }

        if (!empty($request->user()->connectAccount()->capabilities)) {
        }
    }

    public function failure(Request $request)
    {
        if ($request->user()->connect_verified)
            return $this->authorize(false);
        return redirect()->route('identity.index')
            ->with('error', "Your identity could not be verified.");
    }
}
