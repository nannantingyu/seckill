<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeckillOrder extends Model
{
    protected $table = 'seckill_order';
    protected $fillable = ['goods_id', "order_price", "user_id", "shopper_id", "pay_time", "pay_status", "address", "phone", "username","order_no"];
}