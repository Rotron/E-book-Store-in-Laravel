<?php

use App\Http\Middleware\RedirectGuest;

Route::get('/', 'ListingController@index');

Route::get('listing/{name}/{id}', 'ListingController@listing');


/* Admin login */
Route::get('admin', 'AdminLoginController@adminLoginView');
Route::post('admin/login', 'AdminLoginController@login');


Route::group(['middleware' => 'redirectGuest'], function(){
  Route::get('admin/admincp', 'ListingController@admincp');
  Route::get('admin/logout', 'AdminLoginController@logout');

  Route::get('admin/listing/new', 'ListingController@newListingView');
  Route::post('admin/listing/new', 'ListingController@newListing');


  Route::get('admin/listing/edit/{id}', 'ListingController@editListingView');
  Route::post('admin/listing/edit', 'ListingController@editListing');

  Route::delete('admin/listing/delete', 'ListingController@deleteListings');
});
