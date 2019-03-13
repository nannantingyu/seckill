<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 6:20 PM
 */

namespace App\Http\Controllers\Shopper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ShopperAuth;
use App\Http\Requests\SeckillShopper;

class ShopperController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register_page() {
        return view('shopper.shopper_register');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login_page() {
        if (ShopperAuth::check()) {
            return redirect('/seckill_goods_list');
        }

        return view('shopper.shopper_login');
    }

    /**
     * shopper login
     * @param Request $request
     * @return array
     */
    public function login(Request $request) {
        ShopperAuth::login($request->only(['account_name', 'password']));
        return redirect('/seckill_goods_list');
    }

    public function logout(Request $request) {
        ShopperAuth::logout();
        return redirect('/shopper_login');
    }

    /**
     *
     * @param SeckillShopper $request
     * @return mixed
     */
    public function register(SeckillShopper $request) {
        $request->validated();
        $avatar = $request->file('avatar')->store('shopper/avatar/'.date('Ymd'));
        return ShopperAuth::register(array_merge($request->except('avatar'), ["avatar"=>$avatar]));
    }
}