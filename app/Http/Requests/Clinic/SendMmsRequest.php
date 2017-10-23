<?php

namespace App\Http\Requests\Clinic;

use App\Http\Requests\Request;

class SendMmsRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $rules = [
            'acceptor_ids' => 'required|string',
            'mime' => 'required|string',
            'msg_id'=>'required|string'
        ];

        $pos = strpos($this->request->get('mime'),'text/plain');

        if($pos !== false) return $rules + ['text' => 'string'];

        return $rules + [
            'filename' => 'required|string',
            'size' => 'required|numeric',
            'duration' => 'numeric',
            'file' => 'required'
        ];

    }
}
