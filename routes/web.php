<?php
use Illuminate\Support\Facades\Route;

Route::auth();

Route::get('test', 'HomeController@test');
Route::post('/merchantLogin', 'Mer\ShopperController@login');
Route::post('/merchantRegister', 'Shopper\ShopperController@register');
Route::delete('/merchant', 'Mall\MerchantController@store');
Route::put('/merchant', 'Mall\MerchantController@update');

Route::get('/', 'HomeController@index');
Route::get('/merchantRegister', 'Mall\MerchantController@pageRegister')->name('shopper_register');
Route::get('/merchantLogin', 'Mall\MerchantController@login_page');
Route::post('/merchantLogout', 'Mall\MerchantController@logout')->name('shopper_logout');
Route::get('/goodsKill', 'Mall\FlashSaleController@pageFlashSale');
Route::get('/timeDiff', 'Mall\CommonController@localtimeDiff');
Route::post('orderAliPayNotify', "Mall\OrderController@alipayNotify");
Route::get('/killHome.html', "Mall\FlashSaleController@home")->middleware('cache.response:20');
Route::get('/flashSaleList', 'Mall\FlashSaleController@getFlashSale');

Route::middleware('auth')->group(function() {
    Route::post('/flashSaleBuy/{id}/{skid}', "Mall\OrderController@orderPage")->middleware('order_submit');
    Route::get('/orderFill', "Mall\OrderController@pageOrderFill");
    Route::post('/orderFill', "Mall\OrderController@orderFill");
    Route::get('/killUrl', "Mall\FlashSaleController@flashSaleUrl");
    Route::get('/orderPay', "Mall\OrderController@orderPay");
    Route::post('/payStatus', "Mall\OrderController@payStatus");
    Route::get('/orderList', "Mall\OrderController@pageOrderList");
    Route::get('/orderInfo', "Mall\OrderController@pageOrderInfo")->name('orderInfo');
    Route::post('/orderCancel', "Mall\OrderController@cancelOrder")->name('orderCancel');
    Route::post('/orderDelete', "Mall\OrderController@delOrder")->name('orderDelete');
});

Route::prefix('merchant')->group(function() {
    Route::get('/', 'Merchant\HomeController@home');
    Route::get('/login', 'Merchant\AuthController@pageLogin');
    Route::post('/login', 'Merchant\AuthController@login');

    Route::middleware('shopper_auth')->group(function() {
        Route::post('/logout', 'Merchant\AuthController@logout');
        Route::get('/flashSaleGoodsAdd', 'Mall\FlashSaleGoodsController@pageAdd')->name('flashSaleGoodsAdd');
        Route::post('/flashSaleGoodsAdd', 'Mall\FlashSaleGoodsController@store');
        Route::get('/flashGoodsList', 'Mall\FlashSaleGoodsController@pageList')->name('flashGoodsList');
        Route::post('/flashSaleGoodsDel', 'Mall\FlashSaleGoodsController@delete')->name('flashSaleGoodsDel');
        Route::get('/flashSaleGoodsUpdate', 'Mall\FlashSaleGoodsController@pageUpdate')->name('flashSaleGoodsUpdate');
        Route::post('/flashSaleGoodsUpdate', 'Mall\FlashSaleGoodsController@update');

        Route::get('/flashSaleApply', 'Mall\FlashSaleController@pageFlashSaleAdd')->name('flashSaleApply');
        Route::get('/flashSale', 'Mall\FlashSaleController@pageFlashSale');
        Route::get('/flashSaleDelete', 'Mall\FlashSaleController@delete')->name('flashSaleDelete');
        Route::get('/flashSaleUpdate', 'Mall\FlashSaleController@pageFlashSaleUpdate')->name('flashSaleUpdate');
        Route::post('/flashSaleUpdate', 'Mall\FlashSaleController@update');
    });
});

Route::prefix('admin')->group(function() {
    Route::get('/', 'Admin\HomeController@home');
    Route::get('/login', 'Admin\LoginController@loginPage');
    Route::post('/logout', 'Admin\LoginController@logout');
    Route::post('/login', 'Admin\LoginController@login');

    Route::middleware('admin_auth')->group(function() {
        Route::get('/home', 'Admin\HomeController@home');
        Route::get('/shopper', 'Admin\ShopperController@list');
        Route::post('/avatarUploads', 'Admin\UploadController@avatarUploads');
        Route::post('/merchant', 'Admin\MerchantController@store');
        Route::put('/merchant', 'Admin\MerchantController@update');
        Route::delete('/merchant', 'Admin\MerchantController@delete');

        Route::get('/flashSale', 'Admin\SeckillController@list');
        Route::post('/flashSaleCheck', 'Admin\SeckillController@flashSaleCheck');
        Route::post('/flashSaleDelete', 'Admin\SeckillController@flashSaleDelete');
    });
});