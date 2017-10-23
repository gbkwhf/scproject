<?php

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Request;

class UpdateUserProfileRequest extends Request
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
            'name' => 'string',
            'sex' => 'string',
            'blood' => 'string',
            'birthday' => 'date',
            'birth_place' => 'string',
            'live_place' => 'string',
            'live_placeinfo' => 'string',
            'clock' => 'boolean',
            'hospital' => 'string',
            'recollection' => 'string',
            'duty' => 'string',
            'honour' => 'string',
            'skill' => 'string',
            'summary' => 'string',
        ];
    }
}
