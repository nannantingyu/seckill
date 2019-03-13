<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 2:23 PM
 */

namespace App\Events;

use App\Models\User;
class UserRegisted
{
    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
}