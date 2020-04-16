<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile/settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'in:freelancer,client'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $names = explode(' ', $data['name']);
        $user = null;

        if (\App::getLocale() == 'en') {
            $user = User::create(
                [
                    'name' => $data['name'],
                    'email' =>  $data['email'],
                    'first_name' => end($names),
                    'last_name' => reset($names),
                    'account_type' => $data['account_type'],
                    'current_account' => $data['account_type'],
                    'password' => Hash::make($data['password']),
                    'avatar' => asset('users/default.png')
                ]
            );
        } elseif (\App::getLocale() == 'fr') {
            $user = User::create(
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'first_name' => reset($names),
                    'last_name' => end($names),
                    'account_type' => $data['account_type'],
                    'current_account' => $data['account_type'],
                    'password' => Hash::make($data['password']),
                    'avatar' => asset('users/default.png')
                ]
            );
        }
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        SEOMeta::setTitle('Create an Account - Uplance');
        SEOMeta::setDescription('Uplance is a marketplace for finding the best experts in their field with a variety of technology.');
        SEOTools::setCanonical(route('register'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Create an Account - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Uplance is a marketplace for finding the best experts in their field with a variety of technology.');
        SEOTools::opengraph()->setUrl(route('register'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Create an Account - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Uplance is a marketplace for finding the best experts in their field with a variety of technology.');
        SEOTools::twitter()->setUrl(route('register'));

        return view('auth.register');
    }
}
