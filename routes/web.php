<?php


Route::get('/', 'ProductsController@index');
Route::get('product/{product}/{id}', 'ProductsController@product');

Route::post('listener', 'ProductsController@listener');

Route::get('admin-login', 'AdminLoginController@adminLogin');
