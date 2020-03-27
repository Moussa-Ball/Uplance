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

        /**
         * Home route
         */
        Route::get('/', function () {
            return redirect()->route('jobs.index');
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
        Route::get('/reviews/leave/{id}', 'ReviewController@leave')->name('reviews.leave');

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
            Route::get('/~{user}', 'FreelancerController@show')->name('freelancers.show');
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
            Route::get('/~{job}', 'JobController@show')->name('jobs.show');
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
