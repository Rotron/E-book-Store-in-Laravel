<?php

Route::get('/', 'ProductsController@index');
Route::get('product/{product}/{id}', 'ProductsController@product');
Route::get('callback', 'ProductsController@callback');
