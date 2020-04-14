<?php

namespace App\Http\Controllers;

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
        if (Auth::user()->connect_verified)
            return $this->authorize(false);

        if (!empty($request->user()->connectAccount()->capabilities))
            $request->user()->update(['connect_verified' => true]);

        return redirect()->route('identity.index')
            ->with('success', "Your identity has been verified.");
    }

    public function failure(Request $request)
    {
        if (Auth::user()->connect_verified)
            return $this->authorize(false);
        return redirect()->route('identity.index')
            ->with('error', "Your identity could not be verified.");
    }
}
