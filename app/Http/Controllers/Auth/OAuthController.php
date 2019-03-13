<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 5:16 PM
 */

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\OAtuthClients;

class OAuthController extends Controller
{
    private $oauth_site;
    public function __construct() {
        $this->oauth_site = config('APP_URL');
    }
    public function callback(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:3,255',
            'secret' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['code'=>100002, 'message'=>$validator->errors()], 401);
        }

        $client = OAtuthClients::where('name', $request->input('name'))
            ->where('secret', $request->input('secret'))
            ->first();

        $http = new \GuzzleHttp\Client;
        $response = $http->post($this->oauth_site.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $client->id,  // your client id
                'client_secret' => $client->secret,   // your client secret
                'redirect_uri' => $client->redirect,
                'code' => $request->code,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function refresh_token(Request $request) {
        $http = new \GuzzleHttp\Client;

        $response = $http->post('http://yj.cn/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->input("refresh_token"),
                'client_id' => '3',
                'client_secret' => 'urfQSi2EgBCuaEDpGrDlSXhoPy3xjfg1EJFjoXhZ',
                'scope' => '',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function password(Request $request){
        $http = new \GuzzleHttp\Client();

        $response = $http->post('http:/yj.cn/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '4',
                'client_secret' => 't5sY3YhdNZMw86nqirz8GZ9oUaff1ThrQAEL4ZJr',
                'username' => $request->input("username"),
                'password' => $request->input("password"),
                'scope' => '',
            ],
        ]);

        return json_decode((string)$response->getBody(), true);
    }
}