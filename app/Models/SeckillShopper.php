<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/26
 * Time: 11:24 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeckillShopper extends Model
{
    protected $table = 'seckill_shopper';
    protected $fillable = ['id', 'account_name', 'password', 'nick_name', 'scope', 'email', 'avatar'];

    public function goods() {
        return $this->hasMany("App\Models\SeckillGoods", "shopper_id", "id");
    }
}