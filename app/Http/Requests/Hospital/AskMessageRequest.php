<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class AskMessageRequest extends Request
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
            'serial_number' => 'required'
        ];
    }
}
