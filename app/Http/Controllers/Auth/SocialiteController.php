<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class AuthController Used to authenticate the user via social networks.
 *
 * @category Authentification
 * @package  App\Http\Controllers\Auth
 * @author   Moussa Ball <moiseball20155@gmail.com>
 * @license  https://wwww.uplance.co Standard
 * @link     https://wwww.uplance.co
 */
class SocialiteController extends Controller
{
    // Redirect page after login or register via social networks.
    private $redirectTo = '/profile/settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Access not allowed if the user is connected.
        $this->middleware('guest');
    }


    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param string $provider The name of the service provider.
     *
     * @return Response The response obtained from the service provider.
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider,
     * Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in.
     * Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @param string $provider The name of the service provider.
     *
     * @return Response The response obtained from the service provider.
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $trashedUser = User::withTrashed()->where('email', $user->email)->first();
        if (!empty($trashedUser) && $trashedUser->trashed()) {
            return redirect('/login')
                ->with('error', 'This account is inactive and cannot be used. 
                Please contact customer support for further assistance.');
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /*/**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param User $user Socialite user object.
     * @param string $provider The name of the service provider.
     *
     * @return User
     */


    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param $user Socialite user object.
     * @param string $provider The name of the service provider.
     *
     * @return User The user who is found or created.
     */
    public function findOrCreateUser($user, string $provider)
    {
        $authUser = User::where('email', $user->email)->first();
        $names = explode(' ', $user->name);
        $new_user = null;

        if ($authUser) {
            if (!$authUser->avatar) {
                $authUser->avatar = asset('users/default.png');
            }
            $authUser->provider = $provider;
            $authUser->provider_id = $user->id;
            $authUser->save();
            $this->redirectTo = '/jobs';
            return $authUser;
        }

        if (\App::getLocale() == 'en') {
            $new_user = User::create([
                'name' => $user->name,
                'first_name' => end($names),
                'last_name' => reset($names),
                'email' => $user->email,
                'avatar' => asset('users/default.png'),
                'provider' => $provider, // The name of the service provider.
                'provider_id' => $user->id, // The user id from the service provider.
                'email_verified_at' => Carbon::now()
            ]);
        } elseif (\App::getLocale() == 'fr') {
            $new_user = User::create([
                'name' => $user->name,
                'first_name' => reset($names),
                'last_name' => end($names),
                'email' => $user->email,
                'avatar' => asset('users/default.png'),
                'provider' => $provider, // The name of the service provider.
                'provider_id' => $user->id, // The user id from the service provider.
                'email_verified_at' => Carbon::now()
            ]);
        }

        // Mark user as verified.
        $new_user->markEmailAsVerified();

        // Return new user.
        return $new_user;
    }
}
