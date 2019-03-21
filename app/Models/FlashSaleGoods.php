<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlashSaleGoods extends Model
{
    protected $table = 'flash_sale_goods';
    protected $fillable = ['goods_id', 'merchant_id', 'place', 'brand', 'packing', 'weight', 'goods_name', 'detail_pictures'];

    public function shopper()
    {
        return $this->belongsTo("App\Models\Merchant", 'merchant_id', 'id');
    }
}