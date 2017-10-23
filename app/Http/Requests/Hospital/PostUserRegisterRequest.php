<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class PostUserRegisterRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'rn' => 'required',
            'pw' => 'required|alpha_dash|min:8|max:20',
            'pin' => 'required',
            'ct' => 'required|integer|in:1,2,3',
            'dv' => 'string',
            'sim' => 'string',
            'im' => 'string'
        ];
    }
}
