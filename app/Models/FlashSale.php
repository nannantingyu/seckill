<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $table = 'flash_sale';
    protected $fillable = ['goods_id', 'title', 'pictures', 'description', 'ori_price', 'kill_price', 'merchant_id', 'check_at', 'begin_time', 'end_time', 'quantity', 'stock', 'state'];
}