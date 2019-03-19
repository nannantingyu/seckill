<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/26
 * Time: 3:55 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlashSaleGoods extends Model
{
    protected $table = 'flash_sale_goods';
    protected $fillable = ['id', 'merchant_id', 'place', 'brand', 'packing', 'weight', 'goods_name', 'detail_pictures'];

    public function shopper()
    {
        return $this->belongsTo("App\Models\Merchant", 'merchant_id', 'id');
    }
}