<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeckillGoods extends FormRequest
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
            "goods_name"        =>  "required|between:2,256",
            "place"             =>  "required|between:2,256",
            "brand"             =>  "required|between:2,128",
            "weight"            =>  "required|Numeric|min:0|max:10000",
            "detail_pictures"   =>  "required"
        ];
    }
}
