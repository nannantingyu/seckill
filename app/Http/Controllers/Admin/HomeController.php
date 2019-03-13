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

class HomeController extends Controller
{
    public function home(Request $request) {
        return view('admin.home');
    }

    public function userinfo(Request $request) {
        return response()->json(['message'=>'haha']);
    }
}