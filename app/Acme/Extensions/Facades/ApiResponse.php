<?php

namespace App\Acme\Extensions\Facades;

use Illuminate\Support\Facades\Response;

class ApiResponse extends Response
{

    public static function send($code,$msg,$data=[],$http_code=200){

        parent::json([
            'code'=>(string)$code,
            'msg' => $msg,
            'result' =>$data
        ])->setStatusCode($http_code)->send();
        exit();
    }

}