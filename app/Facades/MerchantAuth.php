<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/26
 * Time: 1:37 PM
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class MerchantAuth extends Facade
{
    protected static function getFacadeAccessor() {
        return "merchant_auth";
    }
}