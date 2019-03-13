<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 2:25 PM
 */

namespace App\Listeners;

use App\Events\UserRegisted;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\UserRegister;
use Illuminate\Support\Facades\Mail;

class SendEmailForUserRegisted implements ShouldQueue
{
    public function handle(UserRegisted $event) {
        $user = $event->user;
//        Mail::to($user->email)->send(new UserRegister($user));
    }
}