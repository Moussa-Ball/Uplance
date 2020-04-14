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

    Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')
        ->name('verification.verify');

    // Authentification routes.
    Auth::routes(['verify' => true]);

    /**
     * -------------------------------------------------------------------------------------
     * Routes who not use auth & guest middleware.
     * -------------------------------------------------------------------------------------
     */

    //Show job
    Route::get('/jobs/~{job}', 'JobController@show')->name('jobs.show');

    // Show freelancer
    Route::get('/freelancers/~{user}', 'FreelancerController@show')->name('freelancers.show');

    // Blog routes
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogController@index')->name('blog');
        Route::get('/tag/{slug}', 'BlogController@tag')->name('blog.tag');
        Route::get('/search', 'BlogController@search')->name('blog.search');
        Route::get('/read/~{article}', 'BlogController@read')->name('blog.read');
    });

    // About route
    Route::get('/about', 'HomeController@about')->name('about');

    // Faq route
    Route::get('/faq', 'HomeController@faq')->name('faq');

    // Terms route
    Route::get('/terms', 'HomeController@terms')
        ->name('terms');

    // Contact route
    Route::post('/contact', 'ContactController@send');
    Route::get('/contact', 'ContactController@index')->name('contact');

    // Jobs in city
    Route::get('jobs-in-san-francisco', 'HomeController@jobsInSanFrancisco')
        ->name('jobs-in-san-francisco');
    Route::get('jobs-in-new-york', 'HomeController@jobsInNewYork')
        ->name('jobs-in-new-york');
    Route::get('jobs-in-los-angeles', 'HomeController@jobsInLosAngeles')
        ->name('jobs-in-los-angeles');
    Route::get('jobs-in-miami', 'HomeController@jobsInMiami')
        ->name('jobs-in-miami');

    // Feelancers in country
    Route::get('freelancers-in-united-kingdom', 'HomeController@freelancersInUK')
        ->name('freelancers-in-united-kingdom');
    Route::get('freelancers-in-usa', 'HomeController@freelancersInUSA')
        ->name('freelancers-in-usa');
    Route::get('freelancers-in-france', 'HomeController@freelancersInFrance')
        ->name('freelancers-in-france');

    Route::get('/explore/{slug}', 'HomeController@getFreelancerFromTag')
        ->name('freelancers.tag');


    // Landing Page route
    Route::get('/', 'HomeController@index')->name('home.before');

    /**
     * -------------------------------------------------------------------------------------
     * Routes when the user is not connected.
     * -------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => ['guest']], function () {

        //Search Job For Offline
        Route::get('/search-job', 'HomeController@searchJob')->name('search-job');

        // Social authentification routes.
        Route::get('authentification/{provider}', 'Auth\SocialiteController@redirectToProvider')
            ->name('socialite.auth');
        Route::get('authentification/{provider}/callback', 'Auth\SocialiteController@handleProviderCallback')
            ->name('socialite.callback');
    });


    /**
     * -------------------------------------------------------------------------------------
     * Routes when the user is connected.
     * -------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        /**
         * ----------------------------------------------------
         * Withdrawals
         * ----------------------------------------------------
         */
        Route::group(['prefix' => 'credit'], function () {
            Route::get('/', 'CreditController@index')->name('credit.index');
            Route::post('/buy', 'CreditController@buy')->name('credit.buy');
        });

        /**
         * ----------------------------------------------------
         * Identities
         * ----------------------------------------------------
         */
        Route::group(['prefix' => 'Identity'], function () {
            Route::get('/', 'IdentityController@index')->name('identity.index');
            Route::get('/stripe/verify-identity', 'IdentityController@checkStripeIdentity')->name('identity.stripe.verify');
            Route::get('/stripe/verify-identity', 'IdentityController@checkStripeIdentity')->name('identity.stripe.verify');
            Route::get('/stripe/identity/success', 'IdentityController@success')->name('identity.success');
            Route::get('/stripe/identity/failure', 'IdentityController@failure')->name('identity.failure');
        });

        /**
         * ----------------------------------------------------
         * Withdrawals<
         * ----------------------------------------------------
         */
        Route::group(['prefix' => 'withdraw'], function () {
            Route::get('/', 'WithdrawController@index')->name('withdraws.index');
            Route::get('/get-paid', 'WithdrawController@getPaid')->name('withdraws.paid');
            Route::post('/add-method', 'WithdrawController@addMethod')->name('withdraws.add');
            Route::get('/default/~{withdrawMethod}', 'WithdrawController@setDefault')->name('withdraws.default');
            Route::get('/remove/~{withdrawMethod}', 'WithdrawController@remove')->name('withdraws.remove');
        });

        /**
         * ----------------------------------------------------
         * Subscriptions
         * ----------------------------------------------------
         */
        Route::group(['prefix' => 'membership'], function () {
            Route::get('/', 'SubscriptionController@index')->name('membership');
            Route::get('/subscribe/pro', 'SubscriptionController@subscribeToPro')->name('membership.subscribe.pro');
            Route::get('/subscribe/business', 'SubscriptionController@subscribeToBusiness')->name('membership.subscribe.business');
            Route::get('/subscribe/pro/cancel', 'SubscriptionController@cancelProSubscription')->name('membership.cancel.pro');
            Route::get('/subscribe/business/cancel', 'SubscriptionController@cancelBusinessSubscription')->name('membership.cancel.business');
            Route::get('/subscribe/pro/resume', 'SubscriptionController@resumeProSubscription')->name('membership.resume.pro');
            Route::get('/subscribe/business/resume', 'SubscriptionController@resumeBusinessSubscription')->name('membership.resume.business');
            Route::get('/subscribe/pro/switch-to-business', 'SubscriptionController@switchProSubscriptionToBusiness')
                ->name('membership.switch.pro');
            Route::get('/subscribe/business/switch->to->pro', 'SubscriptionController@switchBusinessSubscriptionToPro')
                ->name('membership.switch.business');
        });

        // BOOKMARKS
        Route::get('/bookmarks', 'BookmarksController@index')->name('bookmarks');
        Route::get('/bookmarks/delete/{bookmark}', 'BookmarksController@destroy')->name('bookmarks.delete');

        // DASHBOARD
        Route::get('/dashboard', 'DashboardController@index')
            ->name('dashboard');

        // REVIEWS
        Route::get('/reviews', 'ReviewController@index')->name('reviews');
        Route::get('/reviews/leave/{review}', 'ReviewController@leave')->name('reviews.leave');

        /**
         * Invoices routes
         */
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/delete/{notification}', 'NotificationController@destroy')->name('notifications.destroy');
            Route::get('/delete', 'NotificationController@destroyAll')->name('notifications.destroy_all');
        });

        /**
         * Invoices routes
         */
        Route::group(['prefix' => 'invoices'], function () {
            Route::get('/', 'InvoiceController@index')->name('invoices.index');
            Route::get('/~{invoice}', 'InvoiceController@show')->name('invoices.show');
            Route::get('/success/~{invoice}', 'InvoiceController@success')->name('invoices.success');
        });

        /**
         * Contracts routes
         */
        Route::group(['prefix' => 'contracts'], function () {
            Route::get('/', 'ContractController@index')->name('contracts.index');
            Route::get('/~{contract}', 'ContractController@show')->name('contracts.show');
            Route::post('/store/~{offer}', 'ContractController@store')->name('contracts.store');
        });

        /**
         * Payments routes.
         */
        Route::group(['prefix' => 'payments'], function () {
            Route::get('/billing-method', 'PaymentController@index')->name('payments.index');
            Route::post('/billing-method/add', 'PaymentController@add')->name('payments.add');
            Route::post('/billing-method/default', 'PaymentController@enableDefaultMethod')->name('payments.default');
            Route::post('/billing-method/remove', 'PaymentController@removePaymentMethod')->name('payments.remove');
        });

        /**
         * Messenger routes.
         */
        Route::group(['prefix' => 'messages'], function () {
            Route::get('/', 'MessengerController@index')->name('messages.index');
            Route::get('/thread~{id}', 'MessengerController@show')->name('messages.thread');
            Route::get('/~{job}-{proposal}', 'MessengerController@createConversation')
                ->name('messages.create');
            Route::get('/~{offer}', 'MessengerController@createConversationForOffer')
                ->name('messages.create.offer');
        });

        /**
         * Offers routes.
         */
        Route::group(['prefix' => 'offers'], function () {
            Route::get('/', 'OfferController@index')->name('offers.index');
            Route::get('/new/~{user}', 'OfferController@create')->name('offers.new');
            Route::get('/destroy/~{offer}', 'OfferController@destroy')->name('offers.destroy');
        });

        /**
         * Proposals routes.
         */
        Route::group(['prefix' => 'proposals'], function () {
            Route::get('/~{id}', 'ProposalController@index')->name('proposals.index');
            Route::get('/list/~{id}', 'ProposalController@show')->name('proposals.list');
            Route::get('/refuse/~{job}-{proposal}', 'ProposalController@destroy')->name('proposals.delete');
        });

        /**
         * Freelancer routes.
         */
        Route::group(['prefix' => 'freelancers'], function () {
            Route::get('/', 'FreelancerController@index')->name('freelancers.index');
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
});
