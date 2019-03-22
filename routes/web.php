<?php
use Illuminate\Support\Facades\Route;

Route::auth();

Route::get('/test', 'HomeController@test');
Route::post('/merchantLogin', 'Mer\ShopperController@login');
Route::post('/merchantRegister', 'Shopper\ShopperController@register');
Route::delete('/merchant', 'Mall\MerchantController@store');
Route::put('/merchant', 'Mall\MerchantController@update');

Route::get('/', 'HomeController@index')->name('/');
Route::get('/merchantRegister', 'Mall\MerchantController@pageRegister')->name('shopper_register');
Route::get('/merchantLogin', 'Mall\MerchantController@login_page');
Route::get('/goodsKill', 'Mall\FlashSaleController@pageFlashSale')->name('goodsKill');
Route::get('/timeDiff', 'Mall\CommonController@localtimeDiff');
Route::post('/orderAliPayNotify', "Mall\OrderController@aliPayNotify")->name('orderAliPayNotify');
Route::get('/killHome.html', "Mall\FlashSaleController@home");
Route::get('/flashSaleList', 'Mall\FlashSaleController@getFlashSale')->name('flashSaleList');

Route::middleware('auth')->group(function() {
    Route::post('/flashSaleBuy/{id}/{skid}', "Mall\OrderController@orderMake")->middleware('order_submit');
    Route::get('/orderFill', "Mall\OrderController@pageOrderFill")->name('orderFill');
    Route::post('/orderFill', "Mall\OrderController@orderFill");
    Route::get('/killUrl', "Mall\FlashSaleController@flashSaleUrl");
    Route::get('/orderPay', "Mall\OrderController@orderPay");
    Route::post('/payStatus', "Mall\OrderController@payStatus");
    Route::get('/orderList', "Mall\OrderController@pageOrderList")->name('orderList');
    Route::get('/orderInfo', "Mall\OrderController@pageOrderInfo")->name('orderInfo');
    Route::post('/orderCancel', "Mall\OrderController@cancelOrder")->name('orderCancel');
    Route::post('/orderDelete', "Mall\OrderController@delOrder")->name('orderDelete');
    Route::get('/orderStatus', "Mall\OrderController@checkOrderStatus")->name('orderStatus');
});

Route::prefix('merchant')->group(function() {
    Route::get('/', 'Merchant\HomeController@home');
    Route::get('/login', 'Merchant\AuthController@pageLogin')->name('merchantLogin');
    Route::post('/login', 'Merchant\AuthController@login');
    Route::get('/register', 'Merchant\AuthController@pageRegister')->name('merchantRegister');

    Route::middleware('merchant_auth')->group(function() {
        Route::post('/logout', 'Merchant\AuthController@logout')->name('merchantLogout');
        Route::get('/goodsAdd', 'Merchant\FlashSaleGoodsController@pageAdd')->name('merchantGoodsAdd');
        Route::post('/goodsAdd', 'Merchant\FlashSaleGoodsController@store');
        Route::get('/goodsList', 'Merchant\FlashSaleGoodsController@pageList')->name('merchantGoodsList');
        Route::post('/goodDel', 'Merchant\FlashSaleGoodsController@delete')->name('merchantGoodsDel');
        Route::get('/goodUpdate', 'Merchant\FlashSaleGoodsController@pageUpdate')->name('merchantGoodsUpdate');
        Route::post('/goodUpdate', 'Merchant\FlashSaleGoodsController@update')->name('updateMerchantGoods');
        Route::get('/goods', 'Merchant\FlashSaleGoodsController@goods')->name('merchantGoods');

        Route::get('/flashSaleApply', 'Merchant\FlashSaleController@pageFlashSaleAdd')->name('merchantFlashSaleApply');
        Route::post('/flashSaleApply', 'Merchant\FlashSaleController@store')->name('applyFlashSale');
        Route::get('/flashSaleList', 'Merchant\FlashSaleController@pageFlashSale')->name('merchantFlashSaleList');
        Route::get('/flashSaleDelete', 'Merchant\FlashSaleController@delete')->name('merchantFlashSaleDelete');
        Route::get('/flashSaleUpdate', 'Merchant\FlashSaleController@pageFlashSaleUpdate')->name('merchantFlashSaleUpdate');
        Route::post('/flashSaleUpdate', 'Merchant\FlashSaleController@update');
    });
});

Route::prefix('admin')->group(function() {
    Route::get('/', 'Admin\HomeController@home');
    Route::get('/login', 'Admin\LoginController@loginPage')->name('adminLogin');
    Route::post('/logout', 'Admin\LoginController@logout')->name('adminLogoutPost');
    Route::post('/login', 'Admin\LoginController@login')->name('adminLoginPost');

    Route::middleware('admin_auth')->group(function() {
        Route::get('/home', 'Admin\HomeController@home')->name('adminHome');
        Route::get('/shopper', 'Admin\ShopperController@list');
        Route::post('/avatarUploads', 'Admin\UploadController@avatarUploads');
        Route::post('/merchant', 'Admin\MerchantController@store');
        Route::post('/merchantUpdate', 'Admin\MerchantController@update');
        Route::post('/merchantDelete', 'Admin\MerchantController@delete');

        Route::get('/flashSale', 'Admin\FlashSaleController@list');
        Route::post('/flashSale', 'Admin\FlashSaleController@store');
        Route::post('/flashSaleCheck', 'Admin\FlashSaleController@checkFlashSale');
        Route::post('/flashSaleDelete', 'Admin\FlashSaleController@flashSaleDelete');
    });
});