<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/27
 * Time: 9:52 AM
 */

namespace App\Http\Middleware;
use App\Facades\MerchantAuth;
use Illuminate\Http\Request;
use Closure;

class MerchantAuthenticate
{
    public function handle(Request $request, Closure $next) {
        if (! MerchantAuth::check()) {
            return redirect('/merchant/login');
        }

        return $next($request);
    }
}