<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class UpdatePasswordRequest extends Request
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
            'opw' => 'required',
            'npw' => 'required|alpha_dash|min:8|max:20',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/'
        ];

    }

}
