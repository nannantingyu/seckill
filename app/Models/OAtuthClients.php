<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 6:02 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OAtuthClients extends Model
{
    protected $table = 'oauth_clients';
    protected $fillable = ['name', 'redirect', 'secret'];
}