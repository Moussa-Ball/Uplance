<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath',
]], function () {
    // Authentification routes.
    Auth::routes(['verify' => true]);

    /**
     * -------------------------------------------------------------------------------------
     * Routes when the user is connected.
     * -------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => ['auth', /*'verified'*/]], function () {

        // Home route
        Route::get('/', function () {
            return redirect()->route('jobs.index');
        });

        /**
         * Messenger routes.
         */
        Route::group(['prefix' => 'messages'], function () {
            Route::get('/', 'MessengerController@index')->name('messages.index');
            Route::get('/~{job_id}-{proposal_id}', 'MessengerController@createConversation')
                ->name('messages.create');
        });

        /**
         * Offers routes.
         */
        Route::group(['prefix' => 'offers'], function () {
            Route::get('/~{user_id}', 'OfferController@index')->name('offers.index');
        });

        /**
         * Proposals routes.
         */
        Route::group(['prefix' => 'proposals'], function () {
            Route::get('/~{id}', 'ProposalController@index')->name('proposals.index');
            Route::get('/list/~{id}', 'ProposalController@show')->name('proposals.list');
            Route::get('/remove/~{job_id}-{proposal_id}', 'ProposalController@destroy')->name('proposals.delete');
        });

        /**
         * Freelancer routes.
         */
        Route::group(['prefix' => 'freelancers'], function () {
            Route::get('/', 'FreelancerController@index')->name('freelancers.index');
            Route::get('/~{id}', 'FreelancerController@show')->name('freelancers.show');
        });

        /**
         * Jobs routes.
         */
        Route::group(['prefix' => 'jobs'], function () {
            Route::get('/', 'JobController@index')->name('jobs.index');
            Route::get('create', 'JobController@create')->name('jobs.create');
            Route::get('edit/~{id}', 'JobController@edit')->name('jobs.edit');
            Route::get('delete/~{id}', 'JobController@destroy')->name('jobs.delete');
            Route::get('manage', 'JobController@manage')->name('jobs.manage');
            Route::get('/~{id}', 'JobController@show')->name('jobs.show');
        });

        /**
         * Profiles routes.
         */
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/settings', 'SettingsController@index')->name('settings');
            if (env('SWITCH_ACCOUNT_FEATURE')) {
                Route::get('/switch-account', 'AccountController@switch')->name('account.switch');
            }
        });

        /**
         * Administration Routes.
         */
        Route::group(['prefix' => 'admin'], function () {
            Voyager::routes();
        });
    });


    /**
     * -------------------------------------------------------------------------------------
     * Routes when the user is disconnected.
     * -------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => ['guest']], function () {
        /**
         * Social authentification routes.
         */
        Route::get('authentification/{provider}', 'Auth\SocialiteController@redirectToProvider')
            ->name('socialite.auth');
        Route::get('authentification/{provider}/callback', 'Auth\SocialiteController@handleProviderCallback')
            ->name('socialite.callback');
    });
});
