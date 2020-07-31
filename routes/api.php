<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::apiResource('products/','Product\ProductController');
Route::put('products/{id}','Product\ProductController@Update');
Route::delete('products/{id}','Product\ProductController@Destroy');

Route::apiResource('category','Category\CategoryController');
Route::delete('category/{id}','Category\CategoryController@Destroy');
