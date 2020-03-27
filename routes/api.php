<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['auth:api']], function () {
    /**
     * ----------------------------------------------------
     * Get the current user who is connected.
     * ----------------------------------------------------
     */
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    /**
     * ----------------------------------------------------
     * Payments routes
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'payments'], function () {
        Route::get('/finish/~{invoice}', 'PaymentController@finishPayment');
        Route::get('/end/~{invoice}', 'PaymentController@paidAndEndContract');
        Route::get('/continue/~{invoice}', 'PaymentController@paidAndContinueContract');
    });

    /**
     * ----------------------------------------------------
     * Offers routes
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'offers'], function () {
        Route::post('/store/~{user}', 'OfferController@store');
    });

    /**
     * ----------------------------------------------------
     * User status & switcher routes.
     * ----------------------------------------------------
     */
    Route::put('/user/{user}/online', 'UserOnlineController');
    Route::put('/user/{user}/offline', 'UserOfflineController');
    Route::put('/user/switcher/{user}/online', 'SwitcherOnlineController');
    Route::put('/user/switcher/{user}/offline', 'SwitcherOfflineController');

    /**
     * ----------------------------------------------------
     * Freelancer routes.
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'freelancers'], function () {
        Route::get('/search', 'FreelancerController@search');
    });

    /**
     * ----------------------------------------------------
     * Messenger routes.
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/discussions/{thread}', 'MessengerController@discussions');
        Route::get('/conversations', 'MessengerController@conversations');
        Route::post('/store/{thread}', 'MessengerController@store');
        Route::put('/active/{id}', 'MessengerController@active');
        Route::put('/mark-as-read/{thread}', 'MessengerController@markAsRead');
        Route::get('/previous/{thread}', 'MessengerController@previousMessages');
    });


    /**
     * ----------------------------------------------------
     * Proposals routes.
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@limit');
        Route::put('/markAsRead/{notification_id}', 'NotificationController@markAsRead');
        Route::get('/markAllAsRead', 'NotificationController@markAllAsRead');
    });

    /**
     * ----------------------------------------------------
     * Proposals routes...
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'proposals'], function () {
        Route::post('/~{id}', 'ProposalController@store');
    });


    Route::post('/attachments', 'AttachmentController@attachments');
    Route::get('/countries', 'CountriesController@countries');

    /**
     * ----------------------------------------------------
     * All routes for jobs (managing, create...)
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'jobs'], function () {
        Route::post('/store', 'JobController@store');
        Route::get('/search', 'JobController@search');
        Route::put('/update/~{id}', 'JobController@update');
    });


    /**
     * ----------------------------------------------------
     * All routes for bookmarking.
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'bookmarks'], function () {
        Route::get('/{bookmark_id}/{bookmark_type}', 'BookmarkController@index');
        Route::post('toggle', 'BookmarkController@toggle');
    });


    /**
     * ----------------------------------------------------
     * All routes for user settings.
     * ----------------------------------------------------
     */
    Route::group(['prefix' => 'profile/settings'], function () {
        Route::post('/update-avatar', 'SettingsController@updateAvatar');
        Route::put('/update-account', 'SettingsController@updateAccount');
        Route::put('/update-profile', 'SettingsController@updateProfile');
        Route::put('/update-password', 'SettingsController@updatePassword');
        Route::delete('/remove-account', 'SettingsController@removeAccount');
        Route::get('/categories', 'SettingsController@categories');
        Route::get('/category', 'SettingsController@category');
        Route::get('/skills', 'SettingsController@skills');
    });

    /**
     * ----------------------------------------------------
     * All reviews routes.
     * ----------------------------------------------------
     */
    Route::post('/store-review~{id}', 'ReviewController@storeReview');
});


/**
 * -----------------------------------------------------------------
 * ---------- API REST FOR DESKTOP & MOBILE APPLICATION ------------
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Api\AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
});

Route::group(['prefix' => 'contracts', 'middleware' => ['auth:api']], function () {
    // Get all contracts for a user from Uplance Desktop App..
    Route::get('/~{user}', 'ContractController@contracts');
});
