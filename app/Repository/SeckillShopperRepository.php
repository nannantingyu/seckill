<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 5:57 PM
 */

namespace App\Repository;
use Illuminate\Support\Facades\DB;
use App\Models\SeckillShopper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SeckillShopperRepository
{
    /**
     * list all shopper
     */
    public function listSeckillShopper() {
        return SeckillShopper::all();
    }

    /**
     * Create seckill_shopper
     * @param $data
     * @return int
     */
    public function createSeckillShopper($data) {
        $data['password'] = Hash::make(config('site.admin_shopper_default_password'));
        $shopper_id = DB::table("seckill_shopper")->insertGetId($data);
        return $shopper_id;
    }

    /**
     * update seckill Shopper
     * @param $data
     * @return int
     */
    public function updateSeckillShopper($data) {
        $shopper = SeckillShopper::find($data['id']);
        if ($shopper->account_name !== $data['account_name']) {
            if (!$this->checkIfAccountNameExist($data['account_name'])) {
                $shopper->account_name = $data['account_name'];
            }
            else throw ValidationException::withMessages(['errors'=>['account_name'=>['account_name existed']]]);
        }

        if ($shopper->email !== $data['email']) {
            if (!$this->checkIfAccountNameExist($data['email'])) {
                $shopper->email = $data['email'];
            }
            else throw ValidationException::withMessages(['errors'=>['email'=>['email existed']]]);
        }

        $shopper->avatar = $data['avatar'];
        $shopper->nick_name = $data['nick_name'];
        $shopper->scope = $data['scope'];

        return $shopper->save();
    }

    /**
     * reset password
     * @param $shopper_id
     * @return mixed
     */
    public function reset_password($shopper_id) {
        return $this->update_password($shopper_id, config('site.admin_shopper_default_password'));
    }

    /**
     * Update shopper password
     * @param $shopper_id
     * @param $new_password
     * @return mixed
     */
    public function update_password($shopper_id, $new_password) {
        $shopper = SeckillShopper::find($shopper_id);
        $shopper->password = Hash::make($new_password);
        return $shopper->save();
    }

    /**
     * delete seckill Shopper
     * @param $id
     * @return int
     */
    public function deleteSeckillShopper($id) {
        $shopper = SeckillShopper::find($id);
        return $shopper && $shopper->delete();
    }

    /**
     * @param $attr
     * @param $value
     * @param null $id
     * @return bool
     */
    private function checkIfShopperExist($attr, $value, $id=null) {
        $shopper = SeckillShopper::where($attr, $value);
        if($id) {
            $shopper = $shopper->where('id', '<>', $id);
        }

        $shopper = $shopper->count();
        return $shopper > 0;
    }

    /**
     * check if account_name exits
     * @param $account_name
     * @param null $id
     * @return boolean
     */
    public function checkIfAccountNameExist($account_name, $id=null) {
        return $this->checkIfShopperExist('account_name', $account_name, $id);
    }

    /**
     * check if email exits
     * @param $account_name
     * @param null $id
     * @return boolean
     */
    public function checkIfEmailExist($email, $id=null) {
        return $this->checkIfShopperExist('email', $email, $id);
    }
}