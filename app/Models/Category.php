<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/15
 * Time: 10:00 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $table = "category";
    public function search_key() {
        return $this->hasMany("App\Models\CategorySearchKey", "category_id", "id");
    }
}