<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/3/11
 * Time: 1:54 PM
 */

namespace App\Http\Controllers\Shopper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * @param Request $request localtime:timestamp
     * @return \Illuminate\Http\JsonResponse
     */
    public function localtimeDiff(Request $request) {
        $request->validate(['localtime'=>'required|integer']);

        return response()->json(['timediff'=>time()*1000 + (int)microtime()*1000 - (int)$request->input('localtime')]);
    }
}