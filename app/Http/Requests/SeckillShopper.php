<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeckillShopper extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "account_name"          =>  "required|between:5,32|unique:seckill_shopper,account_name",
            "nick_name"             =>  "required|between:2,256",
            "scope"                 =>  "required",
            "email"                 =>  "required|unique:seckill_shopper,email|email",
            "avatar"                =>  "required",
        ];
    }
}
