<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 格式化+本地化返回结果
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($message) {
        $message['message'] = locale_message($message['message']) ?? $message['message'];
        return response()->json($message, 200);
    }

    /**
     * 通用页面返回
     * @param $pageName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageResponse($pageName) {
        return view('common.'.$pageName);
    }
}
