<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class SendSMSForResetPasswordRequest extends Request
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
            'ot' => 'required|integer|in:1,2',
            'fasion' => 'required|regex:/^1[34578][0-9]{9}$/'
        ];
    }
}
