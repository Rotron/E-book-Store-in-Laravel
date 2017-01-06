<?php

use App\Http\Middleware\RedirectGuest;

Route::get('/', 'ProductsController@index');
Route::get('product/{product}/{id}', 'ProductsController@product');

Route::post('listener', 'ProductsController@listener');



/* Admin login */
Route::get('admin', 'AdminLoginController@adminLoginView');
Route::post('admin/login', 'AdminLoginController@login');


Route::group(['middleware' => 'redirectGuest'], function(){
  Route::get('admin/admincp', 'AdminLoginController@admincp');
  Route::get('admin/logout', 'AdminLoginController@logout');
});
