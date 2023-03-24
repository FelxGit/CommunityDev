<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('module');
})->name('landing-page');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Voyager::routes();
    // Route::get('/categories','CategoryController@category');
    // Route::get('/tags','TagController@tag');
});
