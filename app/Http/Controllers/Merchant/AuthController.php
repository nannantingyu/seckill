<?php
namespace App\Http\Controllers\Merchant;
use App\Http\Controllers\Controller;
use App\Facades\MerchantAuth;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 商家注册页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageRegister() {
        return view('shopper.shopper_register');
    }

    /**
     * 商家登陆页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageLogin() {
        if (MerchantAuth::check()) {
            return redirect(route('merchantGoodsList'));
        }

        return view('merchant.login');
    }

    /**
     * 商家登陆
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $merchant = MerchantAuth::login($request->only(['account_name', 'password']));
        if (! $merchant instanceof \App\Models\Merchant) {
            return $this->jsonResponse($merchant);
        }

        return $this->jsonResponse(JsonMessage::LOGIN_SUCCESS, ['login_user'=>$merchant->account_name]);
    }

    /**
     * 商家退出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) {
        MerchantAuth::logout();
        return redirect('/shopper_login');
    }

    /**
     * 商家注册
     * @param MerchantRequest $request
     * @return mixed
     */
    public function register(MerchantRequest $request) {
        $request->validated();
        $avatar = $request->file('avatar')->store('uploads/shopper/avatar/'.date('Ymd'));
        MerchantAuth::create(array_merge($request->except('avatar'), ["avatar"=>$avatar]));
        return $this->jsonResponse(JsonMessage::INSERT_SUCCESS);
    }
}