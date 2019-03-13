<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repository\CategoryRepository::class, function() {
            return new \App\Repository\CategoryRepository();
        });
        $this->app->singleton(\App\Repository\SeckillRepository::class, function() {
            return new \App\Repository\SeckillRepository();
        });
        $this->app->singleton(\App\Repository\SeckillGoodsRepository::class, function() {
            return new \App\Repository\SeckillGoodsRepository();
        });

        $this->app->singleton("shopper_auth", function() {
            return new \App\Repository\ShopperLoginRepository();
        });
    }
}