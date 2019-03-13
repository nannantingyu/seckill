<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/27
 * Time: 9:52 AM
 */

namespace App\Http\Middleware;
use ShopperAuth;
use Illuminate\Http\Request;
use Closure;

class ShopperAuthenticate
{
    public function handle(Request $request, Closure $next) {
        if (! ShopperAuth::check()) {
            return redirect('/shopper_login');
        }

        return $next($request);
    }
}