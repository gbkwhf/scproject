<?php

namespace App\Http\Controllers\Baseuser;

use App\CooperationApplyModel;
use Illuminate\Http\Request;
use Acme\Exceptions\ValidationErrorException;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
class CooperationApplyController extends Controller
{
    //申请合作
    public function Cooperation_apply(Request $request)
    {

        $validator = $this->setRules([
            'mobile'=>'required',
            'name'=>'required',
            'content'=>'required|string',
        	'type'=>'required',	
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        
        $params=array(	
            'mobile' => $request->mobile,
            'name' => $request->name,
            'content' => $request ->content,
            'created_at' => date('Y-m-d H:i:s',time()),
        	'type'=>$request->type,	
         );
        if($user_id){
        	$params['user_id']=$user_id;
        }
        $res = CooperationApplyModel::insert($params);
        if($res===false){
    		return $this->setStatusCode(9998)->respondWithError($this->message);
    	}else{
    		return $this->respond($this->format('',true));
    	}
    }
}
