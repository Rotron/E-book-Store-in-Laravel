<?php

use App\Http\Middleware\RedirectGuest;
use App\Http\Middleware\RedirectIfLoggedIn;
use App\Http\Middleware\SetLocale;

use App\Liting;

use Illuminate\Http\Request;

use Faker\Generator as Faker;
use App\Order;
use App\User;
use App\Listing;
use App\OrderSale;
use App\Paypal\Paypal;


// Home. Get mixed listings..
Route::get('/{locale?}', 'ListingController@index')->name('home')->middleware('setLocale');

// Get paid listings
Route::get('{locale?}/listings/paid', 'ListingController@paidListings');
Route::get('{locale?}/listing/paid/{name}/{id}', 'ListingController@paidListing');

// Get free listings
Route::get('listings/free', 'ListingController@freeListings');
Route::get('listing/free/{name}/{id}', 'ListingController@freeListing');

// Login user

// Register user
Route::group(['prefix' => '{locale}/user/', 'middleware' => 'setLocale'], function(){
  Route::get('register', 'UserRegisterController@registerView')->middleware('redirectIfLoggedIn');
  Route::post('register', 'UserRegisterController@register')->middleware('redirectIfLoggedIn');
  Route::get('confirm/{username}/{confirmationCode}', 'UserRegisterController@confirm');
  Route::get('login', 'UserLoginController@LoginView')->name('/user/login')->middleware('redirectIfLoggedIn');
  Route::post('login', 'UserLoginController@login')->middleware('redirectIfLoggedIn');
  Route::get('logout', 'UserLoginController@logout')->middleware('redirectGuest');
});

// UserCP
Route::group(['prefix' => '{locale}/user', 'middleware' => 'redirectGuest'], function(){
  Route::get('usercp', 'UsercpController@index');
});

Route::group(['prefix' => '/{locale}/admin', 'middleware' => array('redirectGuest', 'checkIfAdmin')], function(){
  Route::get('admincp', 'ListingController@admincp');
  Route::get('logout', 'UserLoginController@logout');

  // Create a new listing
  Route::get('listing/new', 'ListingController@newListingView');
  Route::post('listing/new', 'ListingController@newListing');

  // Edit a listing
  Route::get('listing/edit/{id}', 'ListingController@editListingView');
  Route::patch('listing/edit', 'ListingController@editListing');

  // Delete listings
  Route::delete('listing/delete', 'ListingController@deleteListings');

  // Change admin password
  Route::get('change-password', 'ChangeAdminPassword@changePasswordView');
  Route::post('change-password', 'ChangeAdminPassword@changePassword');

  // All users
  Route::get('users', 'ManageUserController@usersView');

  Route::get('user/edit/{id}', 'ManageUserController@userEditView');
  Route::patch('user/edit/{user}', 'ManageUserController@editUser');

  Route::post('search', 'ManageUserController@searchUser');
});

Route::group(['prefix' => '{locale?}'], function(){
    Route::get('contact', 'ContactAdminController@contactView');
    Route::post('send-mail', 'ContactAdminController@validateAdminContact');

    Route::post('callback-paypal', 'OrderController@callbackPaypal');

    Route::get('listing/download/{id}', 'DownloadController@download');

    // Route::get('test/{id}', 'OrderController@alreadyPurchased');

    Route::post('storeorder', 'OrderController@storeOrder');

    Route::get('reset-password', 'ResetPasswordController@resetPasswordView');
    Route::post('reset-password', 'ResetPasswordController@sendResetLink');

    Route::get('set-new-password/{username}/{resetToken}', 'ResetPasswordController@setNewPasswordView');
    Route::post('change-password/', 'ResetPasswordController@changePassword');
});
