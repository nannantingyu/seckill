<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 11:20 AM
 */

namespace App\Http\Controllers\Oauth;
use Illuminate\Http\Request;
use Auth;

class UserController extends \App\Http\Controllers\Controller
{
    /**
     * Get User base info
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo() {
        $user = Auth::user();
        if (!empty($user)) {
            return response()->json($user->only("email", "avatar", "name"));
        }

        return response()->json(['message'=>'Request error', 401]);
    }
}