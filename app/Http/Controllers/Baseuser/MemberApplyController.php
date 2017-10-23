<?php

namespace App\Http\Controllers\Baseuser;

use App\MemberApplyModel;
use Illuminate\Http\Request;
use Acme\Exceptions\ValidationErrorException;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
class MemberApplyController extends Controller
{
    //申请成为会员
    public function Member_apply(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'mobile'=>'required',
            'grade'=>'required',
            'name'=>'required',
            'code'=>'string'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        
        $params=array(
            'mobile' => $request->mobile,
            'code' => $request->code,
            'name' => $request->name,
            'grade' => $request->grade,
            'created_at' => date('Y-m-d H:i:s',time()),
        	'user_id'=>$user_id,	
        );
        $res = MemberApplyModel::insert($params);
        if($res===false){
    		return $this->setStatusCode(9998)->respondWithError($this->message);
    	}else{
    		return $this->respond($this->format('',true));
    	}
    }
}
