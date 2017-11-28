<?php

namespace App\Http\Controllers\HandleProfession;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class JoinManageController  extends Controller
{

	

	public function joinSupplier(Request $request)
	{       
		

		$validator = $this->setRules([
            'ss' => 'required|string', 
            'name' => 'required|string',
			'mobile' => 'required|string',
			'company_name' => 'string',
			'goods_name' => 'required|string',
			'goods_descript' => 'string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

                
        $params=[
			'user_id'=>$user_id,
			'name'=>$request->name,
			'mobile'=>$request->mobile,
			'company_name'=>$request->company_name,
			'goods_name'=>$request->goods_name,
			'goods_descript'=>$request->goods_descript,
        ];
        
        
        $file_name=array();
        $count = count($_FILES);
        for ($i=1;$i<=$count;$i++){
        	if ($request->hasFile('img_'.$i)) {
        		$up_res=uploadPic($request->file('img_'.$i));
        		if($up_res===false){
        			return $this->setStatusCode(6043)->respondWithError($this->message);
        		}else{
        			$file_name[]=$up_res;
        		}
        	}
        }
        $params=[
	        'user_id'=>$user_id,
	        'name'=>$request->name,
	        'mobile'=>$request->mobile,
	        'company_name'=>$request->company_name,
	        'goods_name'=>$request->goods_name,
	        'goods_descript'=>$request->goods_descript,
	        'state'=>0,
	        'created_at'=>date('Y-m-d H:i:s',time()),
	        'img_1'=>empty($file_name[0])?'':$file_name[0],
	        'img_2'=>empty($file_name[1])?'':$file_name[1],
	        'img_3'=>empty($file_name[2])?'':$file_name[2],
        ];        
        $res=\App\JoinSupplierModel::insert($params);
        if($res){
        	return $this->respond($this->format('',true));
        }else{
        	return $this->setStatusCode(9998)->respondWithError($this->message);
        }
	}
	

}