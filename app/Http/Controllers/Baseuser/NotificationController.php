<?php

namespace App\Http\Controllers\Baseuser;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\Clinic\SendMmsRequest;
use App\TraitCollections\NotificationTrait;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    use NotificationTrait;

    public function sendMMS(SendMmsRequest $request)
    {
        return $this->sendMessage($request);
        
    }

    public function getMMS(Request $request)
    {
        $this->validate($request,[
            'msg_id' => 'required|string',
            'thumbnail' => 'required|integer|size:0,1',
        ]);

        return $this->getOtherMessage($request);

    }

    public function getTextMessage(Request $request)
    {
        return $this->getMessage($request);

    }


    public function confirmMessage(Request $request)
    {
        
        $this->validate($request, [
            'serial_number' => 'required'
        ]);

        return $this->ackMessage($request);

    }


}
