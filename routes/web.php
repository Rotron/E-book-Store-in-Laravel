<?php

use App\Http\Middleware\RedirectGuest;

// Get paid listings
Route::get('/', 'ListingController@index');
Route::get('listing/{name}/{id}', 'ListingController@listing');

// Get free listings
Route::get('listings/free', 'ListingController@freeListings');
Route::get('listing/{name}/{id}', 'ListingController@freeListing');

// Admin login
Route::get('admin', 'AdminLoginController@adminLoginView')->middleware('RedirectIfLoggedIn');
Route::post('admin/login', 'AdminLoginController@login');

Route::group(['middleware' => 'redirectGuest'], function(){
  Route::get('admin/admincp', 'ListingController@admincp');
  Route::get('admin/logout', 'AdminLoginController@logout');

  // Create a new listing
  Route::get('admin/listing/new', 'ListingController@newListingView');
  Route::post('admin/listing/new', 'ListingController@newListing');

  // Edit a listing
  Route::get('admin/listing/edit/{id}', 'ListingController@editListingView');
  Route::patch('admin/listing/edit', 'ListingController@editListing');

  // Delete listings
  Route::delete('admin/listing/delete', 'ListingController@deleteListings');

  // Change admin password
  Route::get('admin/change-password', 'ChangeAdminPassword@changePasswordView');
  Route::post('admin/change-password', 'ChangeAdminPassword@changePassword');
});
