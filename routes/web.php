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

Route::get('articlePageList', 'ArticleController@getPageList')->middleware("guest");
Route::get('categoryCrumbs', 'CategoryController@getCategoryCrumbs');
Route::get('categorySearchKey', 'CategoryController@getSearchKey');
Route::get('categoryTree', 'CategoryController@getCategoryTree');
Auth::routes(['verify' => true]);
Route::get('/', 'HomeController@index')->name('home');
Route::get('/oauth/callback', 'Auth\OAuthController@callback');
Route::get('/oauth/refresh_token', 'Auth\OAuthController@refresh_token');
Route::get('/oauth/password', 'Auth\OAuthController@password');
Route::get('/mail', 'HomeController@mail');
Route::get('/api_client', 'ApiController@apiClient');
Route::post('/api_client', 'ApiController@storeApiClient')->name('api_client');
Route::get('/api_client_success', 'ApiController@apiClientSuccess')->name('api_client_success');
Route::get('/user_clients', 'ApiController@userClients');

Route::prefix('oauth')->middleware('auth:api')->group(function() {
    Route::get('user_base_info', 'Oauth\UserController@getUserInfo');
});

Route::post('shopper_login', 'Shopper\ShopperController@login');
Route::post('shopper_register', 'Shopper\ShopperController@register');
Route::post('delete_shopper', 'Shopper\ShopperController@store');
Route::post('update_shopper', 'Shopper\ShopperController@update');


Route::get('shopper_register', 'Shopper\ShopperController@register_page')->name('shopper_register');
Route::get('shopper_login', 'Shopper\ShopperController@login_page');
Route::post('shopper_logout', 'Shopper\ShopperController@logout')->name('shopper_logout');
Route::get('kill', 'Shopper\SeckillController@killGoods');
Route::get('timediff', 'Shopper\CommonController@localtimeDiff');

Route::middleware('auth')->group(function() {
    Route::post('seckill_buy/{id}/{skid}', "Shopper\OrderController@orderPage")->middleware('order_submit');
    Route::get('seckill_order', "Shopper\OrderController@orderFillPage");
    Route::post('fill_order', "Shopper\OrderController@orderFill");
    Route::get('seckill_url', "Shopper\SeckillController@getSeckillUrl");
    Route::get('order_alipay', "Shopper\OrderController@orderAliPay");
    Route::post('check_pay_status', "Shopper\OrderController@checkPayStatus");
    Route::get('pay_success', "Shopper\OrderController@paySuccessPage");
    Route::get('order_list', "Shopper\OrderController@orderList");
    Route::get('orderinfo', "Shopper\OrderController@orderinfoPage")->name('orderinfo');
    Route::get('cancel_order', "Shopper\OrderController@cancelOrder")->name('cancel_order');
    Route::get('del_order', "Shopper\OrderController@delOrder")->name('del_order');
});
Route::post('order_alipay_notify', "Shopper\OrderController@alipayNotify");

Route::middleware('shopper_auth')->group(function() {
    Route::get('seckill_goods_add', 'Shopper\SeckillGoodsController@seckillGoodsAddPage')->name('seckill_goods_add');
    Route::post('seckill_goods_add', 'Shopper\SeckillGoodsController@storeSeckillGoods');
    Route::get('seckill_goods_list', 'Shopper\SeckillGoodsController@listSeckillGoods')->name('seckill_goods_list');
    Route::get('seckill_goods_del', 'Shopper\SeckillGoodsController@deleteSeckillGoods')->name('seckill_goods_del');
    Route::get('seckill_goods_update', 'Shopper\SeckillGoodsController@seckillGoodsUpdatePage')->name('seckill_goods_update');
    Route::post('seckill_goods_update', 'Shopper\SeckillGoodsController@updateSeckillGoods');

    Route::get('apply_for_seckill', 'Shopper\SeckillController@seckillAddPage')->name('apply_for_seckill');
    Route::post('seckill_add', 'Shopper\SeckillController@storeSeckill')->name('seckill_add');
    Route::get('seckill_list', 'Shopper\SeckillController@listSeckill');
    Route::get('seckill_del', 'Shopper\SeckillController@deleteSeckill')->name('seckill_del');
    Route::get('seckill_update', 'Shopper\SeckillController@seckillUpdatePage')->name('seckill_update');
    Route::get('seckill_check', 'Shopper\SeckillController@checkSeckill')->name('seckill_check');
    Route::post('seckill_update', 'Shopper\SeckillController@updateSeckill');
});

Route::get('ad', 'Admin\HomeController@home');
Route::get('ad/login', 'Admin\LoginController@loginPage');
Route::post('ad/logout', 'Admin\LoginController@logout');
Route::prefix('ad')->group(function() {
    Route::post('/login', 'Admin\LoginController@login');
    Route::middleware('admin_auth')->group(function() {
        Route::get('/home', 'Admin\HomeController@home');
        Route::get('/shopper', 'Admin\ShopperController@list');
        Route::post('/uploads_avatar', 'Admin\UploadController@uploads_avatar');
        Route::post('/shopper/store', 'Admin\ShopperController@add_shopper');
        Route::post('/shopper/update', 'Admin\ShopperController@update_shopper');
        Route::post('/shopper/delete', 'Admin\ShopperController@delete_shopper');

        Route::get('/seckill', 'Admin\SeckillController@list');
        Route::post('/seckill/check', 'Admin\SeckillController@checkSeckill');
        Route::post('/seckill/delete', 'Admin\SeckillController@delete_seckill');
    });
});

Route::get('test', 'HomeController@test');
Route::get('alipay_callback', 'Shopper\OrderController@alipaySuccess');