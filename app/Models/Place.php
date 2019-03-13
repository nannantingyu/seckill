<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 11:36 AM
 */

namespace App\Models;


class Place
{
    protected $table = 'place';
    protected $fillable = ['name', 'parent_id', 'code'];
}