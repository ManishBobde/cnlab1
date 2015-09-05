<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Web Routes
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');

Route::get('/', function () {
    return view('welcome');
});
/**
 * API Routes defined for each of the incoming requests
 * Each request after login needs to be validated
 */
Route::group(array('prefix' => 'api/v1'), function() {

    /*####################UserController###############################*/

    Route::post('auth/register', 'UserController@registerUser');

    Route::post('auth/login', 'UserController@loginUser');

    Route::post('auth/verifyOtp', 'UserController@verifyOtp');

    Route::post('college/forgotpassword', 'UserController@forgotPassword');

    Route::post('college/user/changepassword/{id}', 'UserController@changePassword');

    Route::post('college/user/pushnotification/{platform}', 'UserController@setDeviceId');

    Route::get('user/profile','UserController@getUserProfile');

    Route::get('college/user/{slug}/details','UserController@getOtherUserDetails');

    Route::get('college/user/{id}/features','UserController@getUserFeatures');

    Route::get('college/message/allusers/compose/{collegeId}','UserController@getAllUsersWithinTenant');

    Route::get('auth/user/logout', 'UserController@logoutUser');

    /*####################MessageController###############################*/

    Route::get('college/messages/bucket/{bucketname}', 'MessageController@retrieveShortMessages');

    Route::get('college/messages/{msgId}', 'MessageController@retrieveMessageItem');

    Route::post('college/message/compose', 'MessageController@submitMessages');

    Route::post('college/message/delete/{msgId}', 'MessageController@deleteMessage');

    /*####################NewsController###############################*/
    Route::get('college/news', 'NewsController@retrieveShortNewsDesc');

    Route::get('college/news/{newsId}', 'NewsController@retrieveNewsItem');

    Route::post('college/news/add', 'NewsController@createNews');

    Route::post('college/news/delete/{newsId}', 'NewsController@deleteNews');

    Route::post('college/news/{newsId}/edit', 'NewsController@editNews');

    /*####################EventController###############################*/

    Route::get('college/events', 'EventsController@retrieveShortEventDesc');

    Route::get('college/event/{eventId}', 'EventsController@retrieveEventItem');

    Route::post('college/event/add', 'EventsController@createEvent');

    Route::post('college/event/delete/{eventId}', 'EventsController@deleteEvent');

    Route::post('college/event/{eventId}/edit', 'EventsController@editEvent');

    /*####################AdminController###############################*/

    Route::get('user/{id}/features', 'AdminController@retrieveRoleBasedFeatures');

    /*####################ModuleController###############################*/

    Route::get('user/{id}/modules', 'ModuleController@retrieveAccessibleModulesList');

    /*####################CollegeController###############################*/

    Route::post('college/user/register', 'CollegeController@registerCollege');

});

//Route::post('auth/register', 'UserController@postRegister');

Route::post('register/college', 'UserController@postRegister');

