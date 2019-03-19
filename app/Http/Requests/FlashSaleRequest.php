<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlashSaleRequest extends FormRequest
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
            "goods_id"          =>  "required",
            "title"             =>  "required|between:2,128",
            "pictures"          =>  "required",
            "description"       =>  "required|between:2,256",
            "ori_price"         =>  "required|numeric",
            "kill_price"        =>  "required|numeric",
            "begin_time"        =>  "required|date",
            "end_time"          =>  "required|date",
            "quantity"          =>  "required|integer|min:0",
            "stock"             =>  "required|integer|min:0"
        ];
    }
}
