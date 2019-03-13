<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/26
 * Time: 3:55 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeckillGoods extends Model
{
    protected $table = 'seckill_goods';
    protected $fillable = ['id', 'shopper_id', 'place', 'brand', 'packing', 'weight', 'goods_name', 'detail_pictures'];

    public function shopper()
    {
        return $this->belongsTo("App\Models\SeckillShopper", 'shopper_id', 'id');
    }
}