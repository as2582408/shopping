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
Route::get('/signin', 'UserController@getSignin');  //登入畫面
Route::post('/signin', 'UserController@postSignin');//登入
Route::get('/signup', 'UserController@getSignup');  //註冊畫面
Route::post('/signup', 'UserController@postSignup');//註冊
Route::get('/mycenter', 'UserController@getCenter')->middleware(['auth']);      //會員中心
Route::get('/profile', 'UserController@getProfile')->middleware(['auth']);      //修改資料頁面
Route::post('/profile', 'UserController@editProfile')->middleware(['auth']);    //修改資料
Route::get('/password', 'UserController@getPassword')->middleware(['auth']);    //修改密碼頁面
Route::post('/password', 'UserController@editPassword')->middleware(['auth']);  //修改密碼

Route::get('/poi',function () {
    return view('shop.shopIndex');
});


Route::get('/admin',function () {
    return view('admin.adminhome');
});
//登出
Route::post('/admin/logout', 'AdminController@signOut');
//登入畫面
Route::get('/admin/login', 'AdminController@getSignin');
//登入
Route::post('/admin/login', 'AdminController@postSignin');
//後台首頁
Route::get('/admin/center', 'AdminController@adminCenter')->middleware(['admin']);
//帳號管理頁面
Route::get('/admin/account', 'AdminController@account')->middleware(['admin']);
//會員編輯頁面
Route::get('/admin/accountedit/{id}', 'AdminController@editAccountPage')->middleware(['admin']);
//修改會員
Route::post('/admin/accountedit', 'AdminController@editAccount')->middleware(['admin']);
//刪除會員
Route::get('/admin/accountdel/{id}', 'AdminController@delectAccount')->middleware(['admin']); 
//搜尋會員
Route::get('/admin/accountSearch', 'AdminController@searchAccount')->middleware(['admin']);
//商品頁面
Route::get('/admin/products', 'ProductsController@products')->middleware(['admin']);
//新增商品頁面
Route::get('/admin/addProducts', 'ProductsController@addProductsPage')->middleware(['admin']);
//新增商品
Route::post('/admin/addProducts', 'ProductsController@addProducts')->middleware(['admin']);
//刪除商品
Route::get('/admin/delProducts/{id}', 'ProductsController@delectProducts')->middleware(['admin']); 
//修改商品頁面
Route::get('/admin/editProducts/{id}', 'ProductsController@editProductsPage')->middleware(['admin']); 
//修改商品
Route::post('/admin/editProducts', 'ProductsController@editProducts')->middleware(['admin']);
//搜尋商品
Route::get('/admin/productsSearch', 'ProductsController@searchProducts')->middleware(['admin']);

//分類頁面
Route::get('/admin/category', 'CategoryController@index')->middleware(['admin']);
//分類修改頁面
Route::get('/admin/editCategory/{id}', 'CategoryController@editCategoryPage')->middleware(['admin']);
//分類修改
Route::post('/admin/editCategory', 'CategoryController@editCategory')->middleware(['admin']);
//分類刪除
Route::get('/admin/delCategory/{id}', 'CategoryController@delCategory')->middleware(['admin']);
//分類新增頁面
Route::get('/admin/addCategory', 'CategoryController@addCategoryPage')->middleware(['admin']);
//分類新增
Route::post('/admin/addCategory', 'CategoryController@addCategory')->middleware(['admin']);




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
