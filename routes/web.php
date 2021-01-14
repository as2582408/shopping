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
    return redirect('signin');
});
Route::get('/signin', 'UserController@getSignin');      //登入畫面
Route::post('/signin', 'UserController@postSignin');    //登入
Route::get('/signup', 'UserController@getSignup');      //註冊畫面
Route::post('/signup', 'UserController@postSignup');    //註冊
Route::get('/mycenter', 'UserController@getCenter')->middleware(['auth']); //會員中心
Route::get('/profile', 'UserController@getProfile')->middleware(['auth']); //修改資料頁面
Route::post('/profile', 'UserController@editProfile')->middleware(['auth']); //修改資料
Route::get('/password', 'UserController@getPassword')->middleware(['auth']); //修改密碼頁面
Route::post('/password', 'UserController@editPassword')->middleware(['auth']); //修改密碼

Route::get('/poi',function () {
    return view('shop.shopIndex');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
