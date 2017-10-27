<?php

namespace App\Http\Controllers;


use App\Session;
use App\Base;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Acme\Repository\ErrorCode;

use Acme\Exceptions\SessionInvalidException;
use Acme\Exceptions\ValidationErrorException;


abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $statusCode = '1';
    protected $message = '请求成功';
    protected $rules;

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return int
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        //$this->message = ErrCode::getMessage($statusCode);
        $this->message = (new ErrorCode)->getMessage($statusCode);

        return $this;

    }

    private function getErrorMessage()
    {
        return $this->message;
    }


    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found! ')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError()
    {
        //return $this->setStatusCode(9998)->respondWithError($this->message);
        return $this->setStatusCode(9998)->respondWithError($this->message);
    }


//    public function respondConditionError()
//    {
//        return $this->setStatusCode(9999)->respondWithError($this->message);
//    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'code' => $this->getStatusCode(),
            'msg' => $message
        ]);
    }

    /**
     * @param $data
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($data)
    {

//        $keyArr = array_keys($data);
//        if (!in_array('result',$keyArr) && !$flag) {
//            $data['result'] =[];
//        }
//        if (!in_array('result',$keyArr) && $flag) {
//            $data['result'] = new \stdClass();
//        }

        return response()->json($data);

    }

    protected function format($data = [],$flag=FALSE)
    {
        //默认数据格式
        if(!empty($data) || $flag == FALSE){
            return [
                'code' => $this->getStatusCode(),
                'msg' =>  $this->getErrorMessage(),
                'result' => $data
            ];
        //单条记录为空的情况
        }else{
            return [
                'code' => $this->getStatusCode(),
                'msg' => $this->getErrorMessage(),
                'result' => new \stdClass()
            ];
        }

    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($message)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)
            ->respond(['message' => $message]);
    }


    protected function getUserIdBySession($session)
    {

//        $data = Session::where('session', $session)->first(['user_id']);
        $data = Session::where('session', $session)->orWhere('open_id',$session)->first(['user_id']);

        return $data ? $data->user_id : null;

    }
    protected function getUserIdByManageSession($session)
    {
    
    	$data = \App\ManageSession::where('session', $session)->first(['user_id']);
    
    	return $data ? $data->user_id : null;
    
    }

    protected function getUserIdByDoctorSession($session)
    {

        $data = \App\DoctorSession::where('session', $session)->first(['user_id']);

        return $data ? $data->user_id : null;

    }

    protected function getRoleByUserId($uid)
    {
        $role = [];

        $userBase = Base::find($uid);

        $privilege_id = $userBase->privilege_id;

        if (1 == substr($privilege_id, 0, 1)) $role[] = 1;//普通用户

        if (1 == substr($privilege_id, 1, 1)) $role[] = 2;//医生

        if (1 == substr($privilege_id, 2, 1)) $role[] = 3;//健康代表

        if (1 == substr($privilege_id, 3, 1)) $role[] = 4;//志愿者

        return $role;

    }

    protected function _validate($data)
    {
        return !\Validator::make($data,$this->getRules())->fails() ? true : false;

    }

    protected function apiValidate($request,$rule=[],$messages=[],$attributes=[])
    {
         if(\Validator::make($request->all(),$rule,$messages,$attributes)->fails()){
             throw new ValidationErrorException;
         }
    }

}


