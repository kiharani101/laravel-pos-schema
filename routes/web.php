<?php

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('logout', 'Auth\LoginController@custom_logout')->name('custom_logout');

// Category routes
  Route::prefix('categories')->group(function (){
    Route::get('/', 'CategoryController@index')->name('categories.index');
    Route::get('api', 'CategoryController@api')->name('category.api');
    Route::get('all', 'CategoryController@getAll')->name('category.all');
    Route::post('action', 'CategoryController@store_update')->name('category.action');
    Route::post('single', 'CategoryController@show')->name('category.single');
    Route::post('delete', 'CategoryController@destroy')->name('category.delete');
  });

  // Product routes
  Route::prefix('products')->group(function (){
    Route::get('/', 'ProductController@index')->name('products.index');
    Route::get('api', 'ProductController@api')->name('product.api');
    Route::get('all', 'ProductController@getAll')->name('product.all');
    Route::get('sell', 'ProductController@make_sales')->name('product.sell');
    Route::post('action', 'ProductController@store_update')->name('product.action');
    Route::post('single', 'ProductController@show')->name('product.single');
    Route::post('delete', 'ProductController@destroy')->name('product.delete');
  });

  // Sales routes
  Route::prefix('sales')->group(function (){
    Route::get('/', 'SaleController@index')->name('sales.index');
    Route::post('complete', 'SaleController@complete')->name('sales.complete');
  });

  // Rooms routes
  Route::prefix('rooms')->group(function (){
    Route::get('/', 'RoomController@index')->name('rooms');
    Route::get('allocate', 'RoomController@allocate')->name('rooms.allocate');
    Route::get('api', 'RoomController@api')->name('rooms.api');
    Route::post('single', 'RoomController@show')->name('rooms.single');
    Route::post('action', 'RoomController@store_update')->name('rooms.action');
    Route::post('delete', 'RoomController@destroy')->name('rooms.delete');
    Route::post('allocate/complete', 'RoomController@complete')->name('rooms.allocate.complete');
  });

  // Room classes routes
  Route::prefix('rooms/class')->group(function (){
    Route::get('/', 'RoomClassController@index')->name('rooms.class');
    Route::get('api', 'RoomClassController@api')->name('rooms.class.api');
    Route::post('single', 'RoomClassController@show')->name('rooms.class.single');
    Route::post('action', 'RoomClassController@store_update')->name('rooms.class.action');
    Route::post('delete', 'RoomClassController@destroy')->name('rooms.class.delete');
  });
