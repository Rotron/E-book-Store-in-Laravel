<?php

use App\Http\Middleware\RedirectGuest;

// Paid listings..
Route::get('/', 'ListingController@index');
Route::get('listing/{name}/{id}', 'ListingController@listing');

// Free listings..
Route::get('listings/free', 'ListingController@freeListings');
Route::get('listing/{name}/{id}', 'ListingController@freeListing');

/* Admin login */
Route::get('admin', 'AdminLoginController@adminLoginView');
Route::post('admin/login', 'AdminLoginController@login');


Route::group(['middleware' => 'redirectGuest'], function(){
  Route::get('admin/admincp', 'ListingController@admincp');
  Route::get('admin/logout', 'AdminLoginController@logout');

  Route::get('admin/listing/new', 'ListingController@newListingView');
  Route::post('admin/listing/new', 'ListingController@newListing');


  Route::get('admin/listing/edit/{id}', 'ListingController@editListingView');
  Route::patch('admin/listing/edit', 'ListingController@editListing');

  Route::delete('admin/listing/delete', 'ListingController@deleteListings');
});
