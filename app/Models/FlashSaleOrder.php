<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlashSaleOrder extends Model
{
    protected $table = 'flash_sale_order';
    protected $fillable = ['goods_id', "order_price", "user_id", "merchant_id", "pay_time", "pay_status", "address", "phone", "username","order_no"];
}