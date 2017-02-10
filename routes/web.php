<?php

use App\Http\Middleware\RedirectGuest;
use App\Http\Middleware\RedirectIfLoggedIn;
use App\Liting;

use Illuminate\Http\Request;

use Faker\Generator as Faker;
use App\Order;
use App\User;
use App\Listing;
use App\OrderSale;
use App\Paypal\Paypal;
// Home. Get mixed listings..
Route::get('/', 'ListingController@index');

// Get paid listings
Route::get('/listings/paid', 'ListingController@paidListings');
Route::get('listing/paid/{name}/{id}', 'ListingController@paidListing');

// Get free listings
Route::get('listings/free', 'ListingController@freeListings');
Route::get('listing/free/{name}/{id}', 'ListingController@freeListing');

// Login user
Route::get('user/login', 'UserLoginController@LoginView')->name('/user/login')->middleware('redirectIfLoggedIn');
Route::post('user/login', 'UserLoginController@login')->middleware('redirectIfLoggedIn');
Route::get('user/logout', 'UserLoginController@logout')->middleware('redirectGuest');

// Register user
Route::group(['prefix' => 'user'], function(){
  Route::get('/register', 'UserRegisterController@registerView')->middleware('redirectIfLoggedIn');
  Route::post('/register', 'UserRegisterController@register')->middleware('redirectIfLoggedIn');
  Route::get('/confirm/{username}/{confirmationCode}', 'UserRegisterController@confirm');
});

// UserCP
Route::group(['prefix' => 'user', 'middleware' => 'redirectGuest'], function(){
  Route::get('usercp', 'UsercpController@index');
});

Route::group(['prefix' => 'admin', 'middleware' => array('redirectGuest', 'checkIfAdmin')], function(){
  Route::get('admincp', 'ListingController@admincp');
  Route::get('logout', 'AdminLoginController@logout');

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

Route::get('contact', 'ContactAdminController@contactView');
Route::post('send-mail', 'ContactAdminController@validateAdminContact');

Route::post('callback-paypal', 'OrderController@callbackPaypal');

Route::get('listing/download/{id}', 'DownloadController@download');

Route::get('test/{id}', 'OrderController@alreadyPurchased');

Route::post('storeorder', 'OrderController@storeOrder');

Route::get('recover-password', 'RecoverPasswordController@recoverPasswordView');
Route::post('recover-password', 'RecoverPasswordController@recoverPassword');
