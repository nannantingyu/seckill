<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * 上传头像
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function avatarUploads(Request $request) {
        $avatar = $request->file('avatar')->store('uploads/shopper/avatar/'.date('Ymd'));
        return response(['avatar'=>$avatar]);
    }
}