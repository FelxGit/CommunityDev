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
Route::group(['namespace' => 'Api' ], function () {
    Route::post('login', 'UserController@login')->name('login');
    Route::post('register', 'UserController@register')->name('register');
    Route::post('password/email', 'UserController@sendResetLinkEmail');
    Route::post('password/reset', 'UserController@reset');
    Route::get('categories', 'CategoryController@index');
    Route::get('tags', 'TagController@index');
    Route::get('posts', 'PostController@index');

    Route::get('language', function () {
        return response()->json([
            'auth' => \Lang::get('auth'),
            'messages' => \Lang::get('messages'),
            'validation' => \Lang::get('validation')
        ], 200);
    });
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function() {
    Route::post('posts', 'PostController@store');
    Route::get('posts/{id}', 'PostController@show');

    Route::post('likes', 'PostLikeController@store');
    Route::put('likes', 'PostLikeController@update');
    Route::delete('likes', 'PostLikeController@destroy');

    Route::post('favorites', 'PostFavoriteController@store');
    Route::put('favorites', 'PostFavoriteController@update');
    Route::delete('favorites', 'PostFavoriteController@destroy');

    Route::post('comments', 'PostCommentController@store');

    Route::get('notifications', 'NotificationController@index');
});