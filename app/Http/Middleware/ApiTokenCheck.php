<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/19
 * Time: 3:18 PM
 */

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use JWTAuth;
use Closure;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiTokenCheck
{
    public function handle(Request $request, Closure $next, $guard = null) {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            Auth::guard('admin')->setUser($user);
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return $next($request);
    }
}