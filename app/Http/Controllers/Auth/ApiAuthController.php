<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/19
 * Time: 10:12 AM
 */

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins as ThrottleLogin;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Validator;
use App\Models\OAtuthClients;

class ApiAuthController extends BaseController
{
    use ThrottleLogin;

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
            return $this->sendLoginResponse($request);
        }
    }

    /**
     * Send Login success response with token
     * @param Request $request
     */
    function sendLoginResponse(Request $request) {
        $this->clearLoginAttempts($request);
        if (! $token = JWTAuth::attempt($this->credentials($request))) {
            return response()->json(["error"=> "invalid_credential"], 401);
        }

        $ttl = config("jwt.ttl");
        $refresh_ttl = config("jwt.refresh_ttl");
        return response()->json(compact("token", "ttl", "refresh_ttl"));
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
            $this->username()=> "required|string",
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
        return Auth::guard();
    }

    /**
     * Get login credential
     * @param Request $request
     * @return array
     */
    public function credentials(Request $request) {
        return $request->only($this->username(), 'password');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:3,255',
            'secret' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['code'=>100002, 'message'=>$validator->errors()], 401);
        }

        $client = OAtuthClients::where('name', $request->input('name'))
            ->where('secret', $request->input('secret'))
            ->first();

        if (empty($client)) {
            return response(['message'=>"api client 不存在", 'code'=>100001], 401);
        }

        $query = http_build_query([
            'client_id' => $client->id,
            'redirect_uri' => $client->redirect,
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect('/oauth/authorize?'.$query);
    }
}