<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 6:25 PM
 */

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SeckillShopper;
use Illuminate\Validation\ValidationException;

class ShopperLoginRepository
{
    private $shopper;
    protected $loggedOut = false;

    /**
     * shopper login
     * @param $data
     * @param $err_msg string error message if login failed
     * @return \Illuminate\Http\JsonResponse | boolean
     */
    public function login($data) {
        if(!$this->validateLogin($data)) {
            throw ValidationException::withMessages(['error'=>'Params error']);
        }

        $shopper = DB::Table('seckill_shopper')->where("account_name", $data['account_name'])->first();
        if (empty($shopper)) {
            throw ValidationException::withMessages(['error'=>'User not found']);
        }
        else if(!Hash::check($data['password'], $shopper->password)) {
            throw ValidationException::withMessages(['error'=>'Authorize failed']);
        }

        session()->put('shopper_id', $shopper->id);
        session()->put('shopper_account_name', $shopper->account_name);

        $this->shopper = $shopper;
        return true;
    }

    /**
     * shopper logout
     * @return boolean
     */
    public function logout() {
        session()->flush();
        $this->loggedOut = true;
        return true;
    }

    /**
     * validate shopper login
     * @param $data
     * @return bool
     */
    public function validateLogin($data) {
        if (empty($data['account_name']) or empty($data['password'])) {
            return false;
        }

        return true;
    }

    /**
     * @return null|\App\Models\SeckillShopper
     */
    public function shopper() {
        if ($this->loggedOut) {
            return null;
        }

        if (! is_null($this->shopper)) {
            return $this->shopper;
        }

        $shopper_id = $this->id();
        if (! is_null($shopper_id)) {
            $this->shopper = SeckillShopper::find($shopper_id);
        }

        return $this->shopper;
    }

    /**
     * Shopper register
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register($data) {
        $shopper = new SeckillShopper();
        $shopper->account_name = $data['account_name'];
        $shopper->nick_name = $data['nick_name'];
        $shopper->email = $data['email'];
        $shopper->password = Hash::make($data['password']);
        $shopper->avatar = $data['avatar'];
        $shopper->scope = $data['scope'];

        $shopper->save();
        event('register_shopper');

        session()->put('shopper_id', $shopper->id);
        session()->put('shopper_account_name', $shopper->account_name);

        return response(['message'=>'Register success'], 200);
    }

    /**
     * Get login shopper_id
     * @return mixed
     */
    public function id() {
        return session()->get('shopper_id');
    }

    /**
     * Check if login
     * @return bool
     */
    public function check() {
        return !is_null($this->shopper());
    }
}