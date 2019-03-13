<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeckillOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "goods_id"          =>  "required|integer",
            "order_price"       =>  "required|numeric",
            "user_id"           =>  "required|integer",
            "shopper_id"        =>  "required|integer",
            "address"           =>  "required|between:2,512",
            "phone"             =>  "required|numeric|between:2,20",
            "username"          =>  "required|between:2,32",
        ];
    }
}
