<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class PostQualificationRequest extends Request
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
            'auth' => 'required|integer|in:2,3,4',
            'type' => 'required|integer|in:1,2,3,4,5,6,7,8,9',
            'image' => 'required|mimes:jpeg,bmp,png'
        ];
    }
}
