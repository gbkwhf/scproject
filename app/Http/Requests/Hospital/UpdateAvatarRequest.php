<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class UpdateAvatarRequest extends Request
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
            'ss' => 'required|string',
            'user_id' => 'integer|integer',
            'avatar' => 'required|mimes:jpeg,bmp,png'
        ];
    }
}
