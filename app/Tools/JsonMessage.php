<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/3/15
 * Time: 5:54 PM
 */

namespace App\Tools;

class ResponseMessage extends \ArrayIterator {
    public $code;
    public $message;
    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }
}

function aa() {

}
class JsonMessage
{
    // Model
    const DELETE_SUCCESS = ['message'=>'delete_success', 'code'=>100000];
    const UPDATE_SUCCESS = ['message'=>'update_success', 'code'=>100000];
    const INSERT_SUCCESS = ['message'=>'insert_success', 'code'=>100000];
    const MODEL_NOT_FOUND = ['message'=>'model_not_found', 'code'=>100001];

    // Page
    const PAGE_NOT_FOUND = ['message'=>'page_not_found', 'code'=>200001];

    // Auth
    const NEED_LOGIN = ['message'=>'need_login', 'code'=>400003];
    const LOGIN_SUCCESS = ['message'=>'login success', 'code'=>400000];
    const LOGIN_FAILED = ['message'=>'login failed', 'code'=>400001];
    const USER_NOT_FOUND = ['message'=>'user not found', 'code'=>400002];
    const PASSWORD_ERROR = ['message'=>'auth failed', 'code'=>400002];

    // Internal
    const INTERNAL_ERROR = ['message'=>'internal_error', 'code'=>500001];
    const INTERNAL_FORBIDDEN = ['message'=>'internal_forbidden', 'code'=>500002];

    // flash sale
    const FLASH_SUCCESS = ['message'=>'flash_success', 'code'=>600000];
    const FLASH_FINISHED_ERROR = ['message'=>'flash_finished', 'code'=>600001];
    const FLASH_FAILED = ['message'=>'flash_fail', 'code'=>600002];

    const COMMON_SUCCESS = ['message'=>'successful', 'code'=>700001];
}