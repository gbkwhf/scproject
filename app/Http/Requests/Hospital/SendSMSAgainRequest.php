<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class SendSMSAgainRequest extends Request
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
            'service_type' => 'required|in:1,2',
            'im' => 'string'
        ];
    }
}
