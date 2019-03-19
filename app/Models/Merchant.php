<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'flash_sale_merchant';
    protected $fillable = ['id', 'account_name', 'password', 'nick_name', 'scope', 'email', 'avatar'];

    public function goods() {
        return $this->hasMany("App\Models\FlashSaleGoods", "merchant_id", "id");
    }
}