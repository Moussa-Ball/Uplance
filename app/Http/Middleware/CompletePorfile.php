<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CompletePorfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (
                !$user->first_name ||
                !$user->last_name ||
                !$user->email ||
                !$user->account_type ||
                !$user->current_account ||
                !$user->password
            ) {
                return redirect()->route('settings')
                    ->with('warning', 'Please complete your profile before continuing to use uplance.');
            }

            if ($user->current_account == 'freelancer') {
                if (
                    !$user->hourly_rate ||
                    !$user->tagline ||
                    !$user->city ||
                    !$user->address ||
                    !$user->mobile_phone ||
                    !$user->country ||
                    //!$user->category_id ||
                    !$user->skills ||
                    !$user->presentation
                ) {
                    return redirect()->route('settings')
                        ->with('warning', 'Please complete your profile before continuing to use uplance.');
                }
            } elseif ($user->current_account == 'client') {
                if (
                    !$user->tagline ||
                    !$user->city ||
                    !$user->address ||
                    !$user->mobile_phone ||
                    !$user->country
                ) {
                    return redirect()->route('settings')
                        ->with('warning', 'Please complete your profile before continuing to use uplance.');
                }
            }
        }
        return $next($request);
    }
}
