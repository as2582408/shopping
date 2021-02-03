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
Route::group(['middleware' => ['lang']], function () {
    Route::group(['domain' => 'shop.user.net'], function () {
    
        //登入畫面
        Route::get('/signin', 'UserController@getSignin');
        //登入
        Route::post('/signin', 'UserController@postSignin');
        //註冊畫面
        Route::get('/signup', 'UserController@getSignup');
        //註冊
        Route::post('/signup', 'UserController@postSignup');
        //切換語系
        Route::get('/lang/{lang}', 'UserController@chageLang');

        Route::get('/shop', 'ShopController@index')->middleware(['member']);;
    
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/', function () {
                return redirect('shop');
            });
        Route::group(['middleware' => ['member']], function () {
            Route::prefix('shop')->group(function () {
                //商品頁面
                Route::get('/show/{id}', 'shopController@show');
                //加入購物車
                Route::post('/addcart', 'shopController@addCart');
                //快速加入購物車
                Route::get('/quicklyadd/{id}/{price}', 'shopController@quicklyAddCart');
                //搜尋
                Route::get('/search', 'shopController@search');
                //分類
                Route::get('/category/{id}', 'shopController@selectCategory');
                //排序
                Route::get('/orderby/{orderby}/{type}', 'shopController@orderBy');
                //分類排序
                Route::get('/orderByCategory/{orderby}/{type}/{categoryId}', 'shopController@orderByCategory');
                //搜尋排序
                Route::get('/orderBySearch/{orderby}/{type}/{search}', 'shopController@orderBySearch');
                //購物車頁面
                Route::get('/cart', 'shopController@cart');
                //購物車取消
                Route::get('/removecart/{id}', 'shopController@removeCart');
                //結帳確認
                Route::post('/checking', 'shopController@checking');
                //結帳
                Route::post('/checkout', 'shopController@checkout');
            });
            //會員中心
            Route::get('/mycenter', 'UserController@getCenter');
            //修改資料頁面
            Route::get('/profile', 'UserController@getProfile');
            //修改資料
            Route::post('/profile', 'UserController@editProfile');
            //修改密碼頁面
            Route::get('/password', 'UserController@getPassword');
            //修改密碼
            Route::post('/password', 'UserController@editPassword');
            //購物金紀錄
            Route::get('/point', 'UserController@point');
            //訂單頁面
            Route::get('/detail', 'DetailController@userIndex');
            //訂單修改頁面
            Route::get('/editdetail/{id}', 'DetailController@userEditDetailPage');
            //訂單修改
            Route::post('/editdetail', 'DetailController@userEditDetail');
            //訂單刪除
            Route::get('/deldetail/{id}', 'DetailController@userDelDetail');
            //訂單完成
            Route::get('/enddetail/{id}', 'DetailController@endDetail');
            //退貨頁面
            Route::get('/returndetail/{id}', 'DetailController@userReturnDetailPage');
            //退貨
            Route::post('/returndetail', 'DetailController@userReturnDetail');
            //退貨查詢頁面
            Route::get('/return', 'ReturnController@userIndex');
            //退貨詳細頁面
            Route::get('/contentreturn/{id}', 'ReturnController@userContentReturn');
        });
            //客訴頁面
            Route::get('/report', 'ReportController@userIndex');
            //客訴詳細畫面
            Route::get('/reportTalk/{id}', 'ReportController@userTalk');
            //客訴回覆頁面
            Route::get('/reportreply/{id}', 'ReportController@userReportReplyPage');
            //客訴回覆
            Route::post('/reportreply', 'ReportController@userReportReply');
            //客訴新增頁面
            Route::get('/reportnew', 'ReportController@addReportPage');
            //客訴新增
            Route::post('/reportnew', 'ReportController@addReport');
        });
    });
    
    Route::group(['domain' => 'shop.admin.net'], function () {
        Route::get('/',function () {
            return view('admin.adminhome');
        });
        //切換語系
        Route::get('/lang/{lang}', 'UserController@chageLang');

        Route::prefix('admin')->group(function () {
            //登出
            Route::post('/logout', 'AdminController@signOut');
            //登入畫面
            Route::get('/login', 'AdminController@getSignin');
            //登入
            Route::post('/login', 'AdminController@postSignin');

            Route::group(['middleware' => ['admin']], function () {
                //後台首頁
                Route::get('/center', 'AdminController@adminCenter');
                //帳號管理頁面
                Route::get('/account', 'AdminController@account');
                //會員編輯頁面
                Route::get('/accountedit/{id}', 'AdminController@editAccountPage');
                //修改會員
                Route::post('/accountedit', 'AdminController@editAccount');
                //刪除會員
                Route::get('/accountdel/{id}', 'AdminController@delectAccount'); 
                //搜尋會員
                Route::get('/accountSearch', 'AdminController@searchAccount');
            
                //商品頁面
                Route::get('/products', 'ProductsController@products');
                //新增商品頁面
                Route::get('/addProducts', 'ProductsController@addProductsPage');
                //新增商品
                Route::post('/addProducts', 'ProductsController@addProducts');
                //刪除商品
                Route::get('/delProducts/{id}', 'ProductsController@delectProducts'); 
                //修改商品頁面
                Route::get('/editProducts/{id}', 'ProductsController@editProductsPage'); 
                //修改商品
                Route::post('/editProducts', 'ProductsController@editProducts');
                //搜尋商品
                Route::get('/productsSearch', 'ProductsController@searchProducts');
            
                //分類頁面
                Route::get('/category', 'CategoryController@index');
                //分類修改頁面
                Route::get('/editCategory/{id}', 'CategoryController@editCategoryPage');
                //分類修改
                Route::post('/editCategory', 'CategoryController@editCategory');
                //分類刪除
                Route::get('/delCategory/{id}', 'CategoryController@delCategory');
                //分類新增頁面
                Route::get('/addCategory', 'CategoryController@addCategoryPage');
                //分類新增
                Route::post('/addCategory', 'CategoryController@addCategory');
            
                //等級管理頁面
                Route::get('/level', 'LevelController@index');
                //等級修改頁面
                Route::get('/editlevel/{id}', 'LevelController@editLevelPage');
                //等級修改
                Route::post('/editlevel', 'LevelController@editLevel');
                //等級刪除
                Route::get('/dellevel/{id}', 'LevelController@delLevel');
                //等級復原
                Route::get('/redellevel/{id}', 'LevelController@redelLevel');
                //等級新增頁面
                Route::get('/addlevel', 'LevelController@addLevelPage');
                //等級新增
                Route::post('/addlevel', 'LevelController@addLevel');
            
                //優惠管理頁面
                Route::get('/discount', 'DiscountController@index');
                //優惠修改頁面
                Route::get('/editdiscount/{id}', 'DiscountController@editDiscountPage');
                //優惠修改
                Route::post('/editdiscount', 'DiscountController@editDiscount');
                //優惠刪除
                Route::get('/deldiscount/{id}', 'DiscountController@delDiscount');
                //優惠新增頁面
                Route::get('/adddiscount', 'DiscountController@addDiscountPage');
                //優惠新增
                Route::post('/adddiscount', 'DiscountController@addDiscount');
            
                //訂單管理頁面
                Route::get('/detail', 'DetailController@index');
                //訂單修改頁面
                Route::get('/editdetail/{id}', 'DetailController@editDetailPage');
                //訂單修改
                Route::post('/editdetail', 'DetailController@editDetail');
                //訂單取消頁面
                Route::get('/deldetail/{id}', 'DetailController@delDetailPage');
                //訂單取消
                Route::post('/deldetail', 'DetailController@delDetail');
                //訂單出貨
                Route::get('/shipmentdetail/{id}', 'DetailController@shipmentDetail');
            
                //客訴管理
                Route::get('/report', 'ReportController@index');
                //客訴詳細畫面
                Route::get('/reportTalk/{id}', 'ReportController@talk');
                //客訴回覆頁面
                Route::get('/reportreply/{id}', 'ReportController@reportReplyPage');
                //客訴回覆
                Route::post('/reportreply', 'ReportController@reportReply');
            
                //退貨管理
                Route::get('/return', 'ReturnController@index');
                //退貨詳細畫面
                Route::get('/contentreturn/{id}', 'ReturnController@contentReturn');
                //退貨同意
                Route::get('/agreereturn/{id}', 'ReturnController@agreeReturn');
                //退貨拒絕頁面
                Route::get('/refusereturn/{id}', 'ReturnController@refuseReturnPage');
                ////客訴拒絕
                Route::post('/refusereturn', 'ReturnController@refuseReturn');
            });
        });
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
