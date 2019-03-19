<?php
namespace App\Http\Controllers\Mall;
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
        $timeDiff = time()*1000 + (int)microtime()*1000 - (int)$request->input('localtime');
        return response()->json(['timediff'=>$timeDiff]);
    }
}