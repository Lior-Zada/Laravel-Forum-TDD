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

// Route::middleware('throttle:1')->post(...); can use this the allow a user's IP to engage this endpoint maximum 1 per minute

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadController@index');
Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed');
Route::get('/threads/create', 'ThreadController@create');
// override the model route binding from id to 'slug', instead of overloading "getRouteKeyName" method.
Route::get('/threads/{channel:slug}', 'ThreadController@index'); 
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::get('/threads/{channel}/{thread}/replies' , 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies' , 'ReplyController@store');
Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favorites' , 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites' , 'FavoritesController@destroy');

// ->name('profile') allows us to use route('profile' , $item) at the blade.
//  (you dont have to specify $item->name, laravel knows what to take.)
Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');

Route::get('/profiles/{user:name}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user:name}/notifications/{notification}', 'UserNotificationsController@destroy');


Route::group(['prefix' => 'api', 'namespace' => 'Api'], function(){
    
    Route::group(['prefix' => 'users'], function(){
        Route::get('/', 'UsersController@index'); // for mention autocomplete
        Route::post('/{user}/avatar', 'UsersAvatarController@store')->middleware('auth')->name('avatar');
    });
});


