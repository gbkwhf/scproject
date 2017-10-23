<?php

namespace App\Http\Controllers\Baseuser;


use Acme\Exceptions\ValidationErrorException;
use App\AuthCodeModel;
use App\UserCaseImageModel;
use App\UserCaseModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class MyCasesFunctionController extends Controller
{
    //授权码
    public function Auth_code(Request $request)
    {
    	
        $validator = $this->setRules([
            'ss' => 'required|string',
            'type'=>'string'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $code = createPassword();
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $res_auth = AuthCodeModel::select('created_at','id','code')->where('user_id',$user_id)->get()->toArray();

        if ($res_auth){
            $time = date('Y-m-d H:i:s',time()); //当前时间
            $addtime = $res_auth[0]['created_at']; //添加时间
            $endtime = date('Y-m-d H:i:s',strtotime('+3 day',strtotime($addtime))); //失效时间
            //如果时间失效或者type为1，强制更新
            if ($time>$endtime || $request->type == 1){
                $params=array(
                    'user_id'=>$user_id,
                    'code'=>$code,
                    'created_at'=>date('Y-m-d H:i:s',time())
                );
                $res=AuthCodeModel::where('user_id',$user_id)->update($params);
                $res_code = ['code'=>$params['code']];
                return $this->respond($this->format($res_code));
            }else{
                $res_code = ['code'=>$res_auth[0]['code']];
                return $this->respond($this->format($res_code));
            }
        }else{
            //如果该用户的信息不存在
            $params=array(
                'user_id'=>$user_id,
                'code'=>$code,
                'created_at'=>date('Y-m-d H:i:s',time())
            );
            $res=AuthCodeModel::insert($params);
            $res_code = ['code'=>$params['code']];
            return $this->respond($this->format($res_code));
        }
    }

    // 删除图片
    public function Del_img(Request $request){

        $validator = $this->setRules([
            'ss' => 'required|string',
            'id' => 'required',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        $id = explode(',', trim ( $request->id, ',' ));
        $res=UserCaseImageModel::whereIn('id', $id)->delete();
        if($res){
            return $this->respond($this->format('',true));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }

    //添加图片
    public function Add_img(Request $request){
        $validator = $this->setRules([
            'ss' => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        if($request->img_url){//网站上传方式
        	$img_arr=explode('/upload/', $request->img_url);
        	$old=public_path('upload').'/'.$img_arr[1];
        	$new=base_path('storage').'/upload/hospital/'.$img_arr[1];
        	copy($old,$new);
        	$img_params=array(
        			'case_id'=>0,
        			'url'=>'/storage/upload/hospital/'.$img_arr[1]
        	);
        	$res=UserCaseImageModel::insertGetId($img_params);
        }else{//app上传方式
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
        	$img_params=array();
        	//图片插入
        	foreach ($file_name as $val){
        		$img_params=array(
        				'case_id'=>0,
        				'url'=>$val
        		);
        	}
        	$res=UserCaseImageModel::insertGetId($img_params);
        }

        return $this->respond($this->format(array('id'=>$res)));
    }


    //添加与编辑用户病例
    public function Add_case(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'id' => 'required',
            'hospital'=>'required',
            'user_desc'=>'required',
            'time'=>'required',
            'doctor_desc'=>'required',
        	'title'=>'required'	
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $params=array(
            'user_id'=>$user_id,
            'time'=>$request->time,
            'hospital'=>$request->hospital,
            'user_desc'=>$request->user_desc,
            'doctor_desc'=>$request->doctor_desc,
        	'title'=>$request->title,
        );
        $img_id=trim ($request->img_id, ',' );
        if ($request->id != 0){   //编辑
            $data=$request->except('ss','img_id','os_type','version');
            $res_up = UserCaseModel::where('id',$request->id)->update($data); //病例信息更新入库         


            if($res_up!==false){

                if(isset($request->img_id) &&  $request->img_id != '""' && $request->img_id!='') {//如果没有更新图片
                    //$has = DB::select("select id from stj_user_case_image where case_id<>0 and id in($img_id)");
                    $has = DB::select("select id from stj_user_case_image where case_id = 0 and id in($img_id)");
                    if ($has) { //如果图片为新增
                    	if($img_id!='""' && !empty($img_id)){
                    		$u_sql = "UPDATE stj_user_case_image SET case_id=" . $request->id . " WHERE id in ($img_id)";
                    		$res = DB::update($u_sql);
                    	}
                        if ($res === false) {
                            return $this->setStatusCode(9998)->respondWithError($this->message);
                        } else {
                            return $this->respond($this->format('', true));

                        }
                    }
                }
                return $this->respond($this->format('',true));
            }else{
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }
        }else{  //添加
            $case_id = UserCaseModel::insertGetId($params);
			if($img_id!='""' && !empty($img_id)){
				$u_sql = "UPDATE stj_user_case_image SET case_id=" . $case_id . " WHERE id in ($img_id)";
				$res= DB::update($u_sql);				
			}
            if($case_id){
                return $this->respond($this->format('',true));
            }else{
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }
        }

    }



    //删除用户病例
    public function Del_case(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'id'=>'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res_case = UserCaseModel::where('id',$request->get('id'))->delete();
        $res_img = UserCaseImageModel::where('case_id',$request->get('id'))->delete();
        if( $res_case){
            return $this->respond($this->format('',true));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }
    //病例列表与详情
    public function Case_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'code'=>'string',
            'home'=>'string',
            'case_id'=>'string' //列表：不填，详情：病例id
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        
        if(isset($request->ss)){
        	$user_id = $this->getUserIdBySession($request->ss); //获取用户id
        	if (!$user_id)  return $this->setStatusCode(1011)->respondWithError($this->message);                    	
        		
        }

        $start=$request->page<=1?0:(($request->page)-1)*10;//分页
        if ($request->code){ //授权码存在，是医生查看病例
            $res_auth = AuthCodeModel::select('user_id')
                ->where('code',$request->code)
                ->get()->toArray();
            if ($res_auth){
                $uid = $res_auth[0]['user_id'];
                if($request->case_id){    //医生查看详情
                    $case = UserCaseModel::select('id','title','user_id','time','hospital','user_desc','doctor_desc')
                        ->where('id',$request->case_id)->first();//查询病例信息
                    if ($case['id']){//病例id是否存在
                        $img = UserCaseImageModel::select('url','id')->where('case_id',$request->case_id)->get();//查询图片
                        $res=array();
                        $res[0]=$case;
                        $res[0]['img']=$img;
                        return $this->respond($this->format($res));
                    }else{
	                    //病例不存在
            			return $this->setStatusCode(3000)->respondWithError($this->message);                    	
                    }
                }else{  //医生查看病例列表
                    if ($request->home == 1){
                        $res_usercase = UserCaseModel::where('user_id',$uid)->orderBy('time','desc')->select('id','time','hospital','title')->get();
                        $res['list'] = UserCaseModel::where('user_id',$uid)->orderBy('time','desc')->skip($start)->take(10)->select('id','time','hospital','title')->get();
                        $res['count'] = count($res_usercase);
                    }else{
                        $res = UserCaseModel::where('user_id',$uid)->orderBy('time','desc')->skip($start)->take(10)->select('id','time','hospital','title')->get();
                    }

	                return $this->respond($this->format($res));
                }
            }else{
	            //授权码不存在
            	return $this->setStatusCode(3001)->respondWithError($this->message);   
            }
        }else{  //没有授权码，用户自己查看
            if ($request->case_id) {  //用户查看病例详情
                $case = UserCaseModel::select('id','title','user_id','time','hospital','user_desc','doctor_desc')
                    ->where('id',$request->case_id)->first()->toArray();
                $img = UserCaseImageModel::select('url','id')->where('case_id',$request->case_id)->get();
                $case['img']=$img;
                return $this->respond($this->format($case));
            }else{
                //用户查看病例列表
                if ($request->home == 1){
                    $res_usercase = UserCaseModel::where('user_id',$user_id)->orderBy('time','desc')->select('id','time','hospital','title')->get();
                    $res['list'] = UserCaseModel::where('user_id',$user_id)->orderBy('time','desc')->skip($start)->take(10)->select('id','time','hospital','title')->get();
                    $res['count'] = count($res_usercase);
                }else{
                    $res = UserCaseModel::where('user_id',$user_id)->orderBy('time','desc')->skip($start)->take(10)->select('id','time','hospital','title')->get();
                }

                return $this->respond($this->format($res));
            }

        }

    }
}

