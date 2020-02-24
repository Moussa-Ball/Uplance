<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    /**
     * Switch the current either client or freelancer.
     *
     * @param \Illuminate\Http\Request
     */
    public function switch(Request $request)
    {
        if ($request->user()->current_account === 'freelancer') {
            $request->user()->update(['current_account' => 'client']);
        } elseif ($request->user()->current_account === 'client') {
            $request->user()->update(['current_account' => 'freelancer']);
        } else {
            return $this->authorize(true);
        }
        return redirect()->back();
    }
}
