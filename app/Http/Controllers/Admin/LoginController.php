<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/27
 * Time: 3:00 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins as ThrottleLogin;
use Validator;
use Auth;
use JWTAuth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ThrottleLogin;

    /**
     * show admin login page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginPage() {
        if (Auth::guard('admin')->check()) {
            return redirect('/ad/home');
        }

        return view('admin.login');
    }

    /**
     * Login for api user
     * return Token for next identification
     * @param Request $request
     * @return mixed
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request) {
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return redirect('/ad/home');
        }

        throw ValidationException::withMessages(['error'=>'Authorize failed']);
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();

        return redirect('/ad/login');
    }

    /**
     * Attemp to login
     * @param Request $request
     * @return mixed
     */
    public function attemptLogin(Request $request) {
        return $this->guard()->attempt(
            $this->credentials($request), false
        );
    }

    /**
     * Validate login form
     * @param Request $request
     */
    protected function validateLogin(Request $request) {
        $request->validate([
            $this->username()=> "required|string|between:2,32",
            "password"=> "required|string"
        ]);
    }

    /**
     * Get login identify, username or phone or email, or all
     * @return string
     */
    public function username() {
        return 'name';
    }

    /**
     * Get login guard
     * @return mixed
     */
    public function guard() {
        return Auth::guard("admin");
    }

    /**
     * Get login credential
     * @param Request $request
     * @return array
     */
    public function credentials(Request $request) {
        return $request->only($this->username(), 'password');
    }
}