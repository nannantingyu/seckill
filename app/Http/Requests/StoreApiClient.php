<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiClient extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 权限验证
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
            'name'      =>  'required|unique:oauth_clients,name|between:3,255',
            'redirect'  =>  'required'
        ];
    }

    /**
     * 自定义的错误提示
     * @return array
     */
    public function messages(){
        return [
            'name.required' => 'Api名称必须填写',
            'name.unique' => 'Api名称已存在',
            'name.between' => 'Api名称长度必须在3-255个字符',
            'redirect.required'  => '跳转地址必须填写',
        ];
    }
}
