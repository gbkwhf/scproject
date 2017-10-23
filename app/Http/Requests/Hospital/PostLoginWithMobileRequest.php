<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class PostLoginWithMobileRequest extends Request
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
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pin' => 'required',
            'ct' => 'required|integer',
            'push_service_type' => 'required|integer',
            'mid' => 'required'
        ];
    }
}
