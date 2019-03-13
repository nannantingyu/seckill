<?php
namespace App\Models;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class AdminUser extends Authenticatable implements MustVerifyEmail
{
    use HasRolesAndAbilities;
    protected $table = 'admin_user';
    protected $guard = 'admin';
}