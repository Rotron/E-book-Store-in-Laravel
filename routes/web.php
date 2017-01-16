<?php

use App\Http\Middleware\RedirectGuest;
use App\Http\Middleware\RedirectIfLoggedIn;

// Home. Get mixed listings..
Route::get('/', 'ListingController@index');

// Get paid listings
Route::get('/listings/paid', 'ListingController@paidListings');
Route::get('listing/paid/{name}/{id}', 'ListingController@paidListing');

// Get free listings
Route::get('listings/free', 'ListingController@freeListings');
Route::get('listing/free/{name}/{id}', 'ListingController@freeListing');

// Admin login
Route::get('login', 'UserLoginController@LoginView')->middleware('redirectIfLoggedIn');
Route::post('login', 'UserLoginController@login');

Route::get('register', 'UserRegistrationController@registerView');

Route::post('register', function(Rrquest $request){
  dd($request->all());
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
});
