<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/19
 * Time: 3:18 PM
 */

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Auth;
use Closure;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminPermissionCheck
{
    public function handle(Request $request, Closure $next, $guard = null) {
        if (! Auth::guard('admin')->check()) {
            return redirect('/ad/login');
        }

        return $next($request);
    }
}