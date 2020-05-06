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
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadController@index');
Route::post('/threads', 'ThreadController@store');
Route::get('/threads/create', 'ThreadController@create');
// override the model route binding from id to 'slug', instead of overloading "getRouteKeyName" method.
Route::get('/threads/{channel:slug}', 'ThreadController@index'); 
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('/threads/{channel}/{thread}/replies' , 'ReplyController@store');
Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::post('/replies/{reply}/favorites' , 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites' , 'FavoritesController@destroy');

// ->name('profile') allows us to use route('profile' , $item) at the blade.
//  (you dont have to specify $item->name, laravel knows what to take.)
Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');



