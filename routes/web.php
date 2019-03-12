<?php

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

Route::get('/profile/{userId}', 'UserController@index')->name('profile');

Route::get('/friends', 'UserController@friends')->name('friends');

Route::post('/user/add_friend', "UserController@addFriend");
Route::post('/user/accept_friend', "UserController@acceptFriend");
Route::post('/user/change_password', "UserController@changePassword");
Route::post('/user/change_details', "UserController@changeDetails");
