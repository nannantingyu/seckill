<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;
use App\Models\AdminModule;
use App\Models\AdminPermission;
use App\Models\AdminRole;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new AdminUser();
        $user->phone = '13000000000';
        $user->email = 'admin@github.com';
        $user->name = 'admin';
        $user->avatar = 'default';
        $user->password = Hash::make('admin');
        $user->save();


    }
}
