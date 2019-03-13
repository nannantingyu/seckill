<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/27
 * Time: 1:44 PM
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploads_avatar(Request $request) {
        $avatar = $request->file('avatar')->store('shopper/avatar/'.date('Ymd'));
        return response(['avatar'=>$avatar]);
    }
}