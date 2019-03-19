<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repository\Dao\FlashSaleRepository::class, function() {
            return new \App\Repository\Cache\FlashSaleCacheRepository();
        });

        $this->app->singleton(\App\Repository\Dao\FlashSaleGoodsRepository::class, function() {
            return new \App\Repository\Cache\FlashSaleGoodsCacheRepository();
        });

        $this->app->singleton("merchant_auth", \App\Services\MerchantAuth::class);
    }
}