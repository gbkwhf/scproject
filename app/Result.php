<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 10:10
 */

namespace App;


class Result {

    public $success;
    public $statusCode;
    public $requestData;
    public $responseData;
    public $error;
    public function __construct($statusCode=null,$requestData = null, $responseData = null,$error=null )
    {
        $this->success = false;
        if ($statusCode == 200)
            $this->success = true;
        $this->statusCode = $statusCode;
        $this->requestData = $requestData;
        $this->responseData = $responseData;
        $this->error = $error;
    }

    public function getData()
    {
        return $this->responseData;
    }
    public function isSuccess(){
        return $this->success;
    }


}