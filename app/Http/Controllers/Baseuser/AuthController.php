<?php

namespace App\Http\Controllers\Baseuser;

use Acme\Repository\ErrorCode;
use App\Base;
use App\Employees;
use App\Http\Requests\Hospital\PostLoginWithMobileRequest;
use App\Member;
use App\UserPincodeHistoryModel;
use App\UserVersionInfoModel;
use App\Version;
use App\UserExtend;
use Dingo\Api\Console\Command\Cache;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Acme\Extensions\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


use Carbon\Carbon;
use App\Session;
use Acme\Exceptions\ValidationErrorException;
use Acme\Transformers\UserBaseTransformer;
use Acme\Transformers\UserVerTransformer;
use Acme\Transformers\UserExtendTransformer;
use App\TraitCollections\ServerTrait;
use App\TraitCollections\CurlHttpTrait;

class AuthController extends Controller
{

    use ServerTrait,CurlHttpTrait;
    protected $baseUrl;
    protected $url;

    /**
     * @var \Acme\Transformers\UserBaseTransformer
     */
    protected $userBaseTransformer;
    /**
     * @var \Acme\Transformers\UserVerTransformer
     */
    protected $userVerTransformer;
    /**
     * @var \Acme\Transformers\UserExtendTransformer
     */
    protected $userExtendTransformer;
    /**
     * @var \Acme\Transformers\DoctorExtendTransformer
     */
    protected $doctorExtendTransformer;

    public function __construct(
    		//


    )
    {


        $this->baseUrl = getenv('HTTP_REQUEST_URL');

    }

    public function testsend(){
    	sendSms('15353552324','11');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 注册之前发送短信
     **************/

    public function beforeRegisterSendSMS(Request $request)
    {
        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/'
        ])
            ->_validate($request->only('un'));
        if (!$validator) throw new ValidationErrorException;
        $user = Member::where('mobile',$request->un)->first();
        if (!$user){//如果用户不存在
            $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get()->toArray();
            if ($res_un){//如果该用户已申请过验证码
                $res_id = UserPincodeHistoryModel::where('mobile',$request->un)->max('id');
                $res_pin = UserPincodeHistoryModel::where('id',$res_id)->first();
                $pin = $res_pin['original']['pin_code'];
                sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
                $data['msg'] = '短信已发送'.$pin;
                return $this->respond($this->format($data));
            }else{
                $pin = rand('100000','999999');
                sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
                $params = array(
                    'pin_code'=>$pin,
                    'mobile'=>$request->un,
                    'service_type'=>'1',
                    'pin_made_time'=>date('Y-m-d H:i:s',time()),
                );
                $res = UserPincodeHistoryModel::insert($params);
                $data['msg'] = '短信已发送:'.$pin;
                if($res){
                    return $this->respond($this->format($data));
                }else{
                    return $this->setStatusCode(9998)->respondWithError($this->message);
                }
            }
        }else{
            $data['msg'] = '用户已存在，直接登录';
            return $this->respond($data);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 注册
     **************/
    public function register(Request $request)
    {

        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pw' => 'required|min:8|max:20',
            'name' => 'string',
            'sex' => 'string',
            'pin' => 'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $max_id = UserPincodeHistoryModel::where('mobile',$request->un)->max('id');//获取id最大值
        $max = UserPincodeHistoryModel::where('id',$max_id)->first();//获取最大id值的信息
        if ($max['pin_try_accmulation'] != 3) {
            if ($max['is_succ'] == '0') { //状态为0，代表没有被验证
                if ($max['pin_code'] == $request->pin) {
                    $data = array(
                        'mobile' => $request->un,/**/
                        'password' => md5($request->pw),
                        'created_at' => date('Y-m-d H:i:s'),
                        'name' => empty($request->name) ? '游客' : $request->name,
                       // 'sex' => $request->sex
                    );
                    $had = Member::where('mobile', $request->un)->first();
                    if ($had) {//手机号已存在
                        return $this->setStatusCode(1002)->respondWithError($this->message);
                    }
                    $res = Member::insertGetId($data);
                    $is_succ = array('is_succ' => '1', 'pin_accmulation_time' => date('Y-m-d H:i:s', time()));
                    UserPincodeHistoryModel::where('pin_code', $request->pin)->update($is_succ);
                    $version = array(
                        'user_id' => $res,
                        'base_ver' => 1,
                        'last_update_date' => date('Y-m-d H:i:s', time()),
                    );
                    UserVersionInfoModel::insert($version);

                    if ($res) {
                        return $this->respond($this->format([], true));
                    } else {
                        return $this->setStatusCode(9998)->respondWithError($this->message);
                    }
                } else {//验证码输入错误，每次将重试次数加1
                    $pin_try_accmulation = array('pin_try_accmulation' => $max['pin_try_accmulation'] + 1);
                    UserPincodeHistoryModel::where('id', $max_id)->update($pin_try_accmulation);
                    $data['msg'] = '验证码输入有误，请重试';
                    return $this->respond($data);
                }
            }elseif(!isset($max_id)){ //新注册用户，没有申请过验证码，随便填
                $data['msg'] = '验证码错误';
                return $this->respond($data);
            }else{
                $data['msg'] = '账号已存在';
                return $this->respond($data);
            }
        }elseif($max['pin_try_accmulation'] == 3){//如果重试次数超过3次，调用重发短信接口
            $data['msg'] = '验证码错误！';
            return $this->respond($data);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 普通方式登陆（验证用户名和密码）
     **************/
    public function login(Request $request)
    {
        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pw' => 'required|string',
            'mid' => 'string',
            'pushsvc' => 'integer',
            'ct' => 'required|integer'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        
        //手机号未注册，提示未注册
        $had_mobile=\App\Member::where('mobile',$request->un)->first();
        if(!$had_mobile){
        	return $this->setStatusCode(1014)->respondWithError($this->message);        	 
        }
        
        

        $user_info=\App\Member::where('mobile',$request->un)->where('password',md5($request->pw))->first();
        if(!$user_info){
        	return $this->setStatusCode(1010)->respondWithError($this->message);        	 
        }
        $client_type = $request->get('ct');
		$data=array(
					'user_name'=>$user_info->name,
					'user_id'=>$user_info->user_id,
					'mobile'=>$user_info->mobile,
					
					);
        if($client_type==3){
        	$push_service = '';
        }else{
        	$push_service = $request->get('pushsvc');
        	$mid = $request->get('mid');
        	if ($client_type==2){  //add  2014 09 18   by song
        		$oldSession=Session::where('mid',$mid)->first();
        		if (!is_null($oldSession) && $oldSession['user_id']!=$user_info['user_id']){
        			//不为空同时不等于当前用户id说明该设备已经有一个账号登录，需要清空消息队列和重置旧账号的mid信息
        			//重置旧账号的mid信息
        			\Log::info("检测到iphone设备登录多次账号，清空上一个账号的消息列表");
        			$oldSession->update(['mid'=>'0000','session'=>'']);
        			$tcpConnection=app('\Acme\Repository\TcpConnection',[$oldSession['mec_ip'],$oldSession['mec_port']]);
        			//清空消息队列
        			if(!$tcpConnection->isConnected()){
        				\Log::error(sprintf("Connect to MEC server fail.mec ip=%s,port=%s",$oldSession['mec_ip'],$oldSession['mec_ip']));
        			}
        			//ACK MY_MID SERIAL
        			$inf_value=0x7fffffff;
        			$message = "ACK " . $mid . " " . $inf_value . "\n";
        			$result = $tcpConnection->tcpSend($message);
        			if(!$result){
        				\Log::error(sprintf("Send message to MEC fail.message=%s",$message));
        			}
        		}
        	}
        }
        //获取分配服务器信息
        $serverArr = $this->getDispatchServerInfo('stj');
        if ($data) {
            $user_id = $data['user_id'];
            $data = array_merge($data, $serverArr);
            $session = (new Session)->createSession($user_id);
        }
        //更新session相关信息
        (new Session)->addSession($user_id, [
            'user_id' => $user_id,
            'client_type' => $client_type,
            'session' => $session,
            'mid' => $client_type != 2 ? $user_id : $mid,
            'push_service_type' => $push_service,
            'mec_ip' => $serverArr['mec_ip'],
            'mec_port' => $serverArr['mec_port'],
            'lps_ip' => $serverArr['lps_ip'],
            'lps_port' => $serverArr['lps_port'],
            'last_get_session_date' => Carbon::now(),
            'session_hash' => abs(crc32($session))
        ]);
        $sessionRes = Session::where('user_id', $user_id)
            ->first(['user_id', 'session', 'push_service_type']);
        $sessionArr = $sessionRes->toArray();
        $data = array_merge($sessionArr,$data,$serverArr);


        return $this->respond($this->format($data,true));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 重发短信
     ***************/
    public function sendSMSAgain(Request $request)
    {

        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'service_type' => 'integer'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        $user = Member::where('mobile',$request->un)->first();
        $is_sicc = UserPincodeHistoryModel::where('is_succ','1')->first();

        if ($request->service_type == '1' || $request->service_type ==''){
            $service_type='1';
            if ($user){
        		return $this->setStatusCode(1002)->respondWithError($this->message);        		            	                
            }
        }else{
        	if (!$user){
        		return $this->setStatusCode(1014)->respondWithError($this->message);        		
        	}
            $service_type=3;
        }
        $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get();
        if(count($res_un)>0){
        	$max = $res_un[count($res_un)-1]['pin_made_time'];//最后一条验证码创建时间
        	$interval_f = date('Y-m-d H:i:s',strtotime("$max+5 minute"));//间隔时间
        	$datetime = date('Y-m-d H:i:s',time());//当前时间        	

        	//如果该手机号码申请的注册码超过六条
        	if (count($res_un)>='6'){
        		$min = $res_un[count($res_un)-'6']['pin_made_time'];//倒数第六条验证码创建时间
        		$time = date('Y-m-d 00:00:00');//当天时间零点
        		//如果倒数第六条验证码时间，不小于当天时间零点，说明今天发满6条验证码
        		if ($min>=$time){
        			$data['msg'] = '今天申请过6次验证码，请明天再试';
        			return $this->respond($data);
        		}else{
        			if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
        				$pin = rand('100000','999999');
        				sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
        				$params = array(
        						'pin_code'=>$pin,
        						'mobile'=>$request->un,
        						'service_type'=>$service_type,
        						'pin_made_time'=>date('Y-m-d H:i:s',time()),
        				);
        				$res = UserPincodeHistoryModel::insert($params);
        				$data['msg'] = '短信已发送:'.$pin;
        				if($res){
        					return $this->respond($this->format($data));
        				}else{
        					return $this->setStatusCode(9998)->respondWithError($this->message);
        				}
        			}else{
        				$data['msg'] = '5分钟之内已经产生过一次验证码';
        				return $this->respond($data);
        			}
        		}
        	}else{//该手机号申请验证码不超过6条
        		if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
        			$pin = rand('100000','999999');
        			sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
        			$params = array(
        					'pin_code'=>$pin,
        					'mobile'=>$request->un,
        					'service_type'=>$service_type,
        					'pin_made_time'=>date('Y-m-d H:i:s',time()),
        			);
        			$res = UserPincodeHistoryModel::insert($params);
        			$data['msg'] = '短信已发送:'.$pin;
        			if($res){
        				return $this->respond($this->format($data));
        			}else{
        				return $this->setStatusCode(9998)->respondWithError($this->message);
        			}
        		}else{
        			$data['msg'] = '5分钟之内已经产生过一次验证码';
        			return $this->respond($data);
        		}
        	}

        	
        }else{
        	
        	$pin = rand('100000','999999');
        	sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
        	$params = array(
        			'pin_code'=>$pin,
        			'mobile'=>$request->un,
        			'service_type'=>$service_type,
        			'pin_made_time'=>date('Y-m-d H:i:s',time()),
        	);
        	$res = UserPincodeHistoryModel::insert($params);
        	$data['msg'] = '短信已发送:'.$pin;
        	if($res){
        		return $this->respond($this->format($data));
        	}else{
        		return $this->setStatusCode(9998)->respondWithError($this->message);
        	}
        	
        	
        	
        }


    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 版本更新
     **************/
    public function validateVersion(Request $request)
    {

        $validator = $this->setRules([
            'os_language' => 'required|integer',
            'os_type' => 'required',
            'client_version' => 'required',
            'part_download' => 'integer'
        ])->_validate($request->all());

        if (!$validator) throw new ValidationErrorException;

        $serverVer = \Cache::get('update'.$request->get('os_language').$request->get('os_type'),function() use ($request){
            $serverVer=\DB::table('stj_updateinfo')
                ->where('os_language', $request->get('os_language'))
                ->where('os_type', $request->get('os_type'))
                ->first();
            if($serverVer){
                \Cache::put('update'.$request->get('os_language').$request->get('os_type'),$serverVer,\Carbon\Carbon::now()->addDay(1));
                return $serverVer;/**/
            }else{
                return false;
            }
        });
        if (!$serverVer) {
            return $this->setStatusCode(1012)->respondWithError($this->message);
        }
        $compare = version_compare($serverVer->update_version, $request->get('client_version'), '>');
        if (!$compare) {
            return $this->setStatusCode(1012)->respondWithError($this->message);
        }
        return $this->respond($this->format([
            'download_url' => $serverVer->download_url,
            'update_version' => $serverVer->update_version,
            'update_description' => $serverVer->update_description,
            'file_name' => $serverVer->file_name,
            'file_size' => $serverVer->file_size,
            'upload_date' => $serverVer->upload_date,
            'minimal_version' => $serverVer->minimal_version
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 重置密码前发送短信
     **************/
    public function resetPasswordBySMS(Request $request)
    {

        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
           // 'service_type' => 'required'//1:注册3：手机找回密码
        ])
            ->_validate($request->only('un'));
        if (!$validator) throw new ValidationErrorException;

        $user = Member::where('mobile',$request->un)->first();
        if ($user || $request->type == '1'){//如果用户存在，或者type=1代表从注册前发短信跳转过来
            $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get();
            $max = $res_un[count($res_un)-1]['pin_made_time'];//最后一条验证码创建时间
            $interval_f = date('Y-m-d H:i:s',strtotime("$max+5 minute"));//间隔时间
            $datetime = date('Y-m-d H:i:s',time());//当前时间
            if (count($res_un)>='6'){//如果该手机号码申请的注册码超过六条
                $min = $res_un[count($res_un)-'6']['pin_made_time'];//倒数第六条验证码创建时间
                $time = date('Y-m-d 00:00:00');//当天时间零点
                if ($min>=$time){//如果倒数第六条验证码时间，不小于当天时间零点，说明今天发满6条验证码
                    $data['msg'] = '今天申请过6次验证码，请明天再试';
                    return $this->respond($data);
                }else{
                    if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
                        $pin = rand('100000','999999');
                        sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
                        $params = array(
                            'pin_code'=>$pin,
                            'mobile'=>$request->un,
                            'service_type'=>'3',
                            'pin_made_time'=>date('Y-m-d H:i:s',time()),
                        );
                        $res = UserPincodeHistoryModel::insert($params);
                        $data['msg'] = '您的验证码是:'.$pin;
                        if($res){
                            return $this->respond($this->format($data));
                        }else{
                            return $this->setStatusCode(9998)->respondWithError($this->message);
                        }
                    }else{
                        $data['msg'] = '5分钟之内已经产生过一次验证码';
                        return $this->respond($data);
                    }
                }
            }else{//该手机号申请验证码不超过6条
                if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
                    $pin = rand('100000','999999');
                    sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
                    $params = array(
                        'pin_code'=>$pin,
                        'mobile'=>$request->un,
                        'service_type'=>'3',
                        'pin_made_time'=>date('Y-m-d H:i:s',time()),
                    );
                    $res = UserPincodeHistoryModel::insert($params);
                    if($res){
                        $data['msg'] = '短信已发送:'.$pin;
                        return $this->respond($this->format($data));
                    }else{
                        return $this->setStatusCode(9998)->respondWithError($this->message);
                    }
                }else{
                    $data['msg'] = '5分钟之内已经产生过一次验证码';
                    return $this->respond($data);
                }
            }
        }else{
         return $this->setStatusCode(1014)->respondWithError($this->message);        	
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 重置密码
     **************/
    public function resetPassword(Request $request)
    {
        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pin'=>'required',
            'pw'=>'required|min:8|max:20',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res_member = Member::where('mobile',$request->un)->first();
        if ($res_member){//如果用户存在
            $max_id = UserPincodeHistoryModel::where('mobile',$request->un)->max('id');//获取id最大值
            $max = UserPincodeHistoryModel::where('id',$max_id)->first();//获取最大id值的信息
            if ($max['pin_code'] == $request->pin){
                if ($max['is_succ']!=1){
                    $pw = array('password'=>md5($request->pw));
                    $is_succ = array('is_succ'=>'1','pin_accmulation_time'=>date('Y-m-d H:i:s',time()));
                    $res = Member::where('mobile',$request->un)->update($pw);
                    UserPincodeHistoryModel::where('pin_code',$request->pin)->update($is_succ);//将验证码的状态更新为已使用
                    $data['msg'] = '密码重置成功';
                    if($res === false){
                        return $this->setStatusCode(9998)->respondWithError($this->message);
                    }else{
                        return $this->respond($this->format($data));
                    }
                }else{//如果验证码被使用过
                    $data['msg'] = '验证码错误';
                    return $this->respond($data);
                }
            }else{//如果验证码输入有误
                $data['msg'] = '验证码错误';
                return $this->respond($data);
            }
        }else{
        	 return $this->setStatusCode(1014)->respondWithError($this->message);        	
        	
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 修改密码
     */
    public function changePassword(Request $request)
    {
                $validator = $this->setRules([
            'ss' => 'required',
            'npw' => 'required|min:8|max:20',
            'opw' => 'required',
        ])
            ->_validate($request->all());

        if (!$validator) throw new ValidationErrorException;
        
    	$user_id = $this->getUserIdBySession($request->ss); //获取用户id		
        if(!$user_id){
        	return $this->setStatusCode(1011)->respondWithError($this->message);        	
        }
        $had=\App\Member::where('user_id',$user_id)->where('password',md5($request->opw))->first();
        if(!$had){
        	return $this->setStatusCode(6102)->respondWithError($this->message);
        }
        $data=array(
        	'password'=>md5($request->npw),
        );
        $res=\App\Member::where('user_id',$user_id)->update($data);

        if($res === false){
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }else{
            return $this->respond($this->format([],true));
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 获取用户头像
     */
    public function getAvatar(Request $request)
    {
        $validator = $this->setRules([
            'ss'=>'required|string',
            'uid' => 'string',  //非必填，如果填写则表示获取该用户的，否则就代表获取自己的
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        if (isset($request->uid)){//uid存在，获取他人头像
            $uid = Member::where('user_id',$request->uid)->first();
            if ($uid){
                if (!isset($uid['image'])){//没有上传头像
                    $data['msg'] = '该用户没有上传头像';
                    return $this->respond($data);
                }else{
                    return $this->respond($this->format($uid['image']));
                }
            }else{
                $data['msg'] = '该用户不存在';
                return $this->respond($data);
            }
        }else{//uid不存在，获取用户自己的头像
            $res = Member::where('user_id',$user_id)->first();
            if (!isset($res['image'])){//没有上传头像
                $data['msg'] = '该用户没有上传头像';
                return $this->respond($data);
            }else{
                return $this->respond($this->format($res['image']));
            }
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 更新用户头像
     **************/
    public function UpdateUser_img(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        
        
        
        if($request->img_url){//网站上传方式
        	$img_arr=explode('/upload/', $request->img_url);
        	$old=public_path('upload').'/'.$img_arr[1];
        	$new=base_path('storage').'/upload/hospital/'.$img_arr[1];
        	copy($old,$new);
        	\Image::make($new)->resize(100, 100)->save(base_path('storage').'/upload/hospital/'.'thu_'.$img_arr[1]);        	
        	$res = Member::where('user_id',$user_id)->update(array('image'=>'/storage/upload/hospital/'.$img_arr[1]));
        }else{        	
        	if ($request->hasFile('image')){//判断是否有图片上传
        		$up_res=uploadPic($request->file('image'));//上传图片
        		if($up_res===false){
        			return $this->setStatusCode(6043)->respondWithError($this->message);
        		}else{
        			$file_name['image']=$up_res;
        		}
        	}
        	$res = Member::where('user_id',$user_id)->update($file_name);
        }
        $image=Member::where('user_id',$user_id)->select('image')->first();
         
        $img_arr=explode('/hospital/', $image['image']);
        $new_data['source_image_url']=$image['image'];
        $new_data['thumbnail_image_url']=$img_arr[0].'/hospital/thu_'.$img_arr[1];
        if($res === false){

            return $this->setStatusCode(9998)->respondWithError($this->message);
        }else{
            return $this->respond($this->format($new_data));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 获取用户基本信息
     **************/
    public function Profile(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $profile = Member::where('user_id',$user_id)
        			->first();
        $res_version = UserVersionInfoModel::where('user_id',$user_id)->first();
        if ($res_version){
            $base_ver = $res_version['base_ver'];
        }else{
            $base_ver = 1;
        }
        
        //缩略图
        if($profile['image'] !=''){
        	$img_arr=explode('/hospital/', $profile['image']);
        	$thu_img=$img_arr[0].'/hospital/thu_'.$img_arr[1];        	
        }else{
        	$thu_img='';
        }
        
        if(!file_exists(public_path('/upload/qccode/').$user_id.'_qccode.png')){
			//dd(public_path('upload/qccode/stj-icon.png'));
        	//为用户生成邀请二维码图片
        	\QrCode::format('png')->size(200)->errorCorrection('Q')->margin(0)->merge('/public/upload/qccode/stj-icon.png', .2)->generate(env('APPLY_URL').'?qc='.$profile['mobile'],public_path('/upload/qccode/').$user_id.'_qccode.png');
        	
        }
        if ($profile['work_address'] == ""){
            $work_address = '';
        }else{
            $work_address = $profile['work_address'];
        }
        
 
        
        if($_SERVER['SERVER_PORT'] == 443){
        	$http='https://';
        }else{
        	$http='http://';
        }
        
        
        $params[] = array(
        	'user_id'=>	$user_id,
            'mobile'=>$profile['mobile'],
            'sex_id'=>$profile['sex'],
            'live_place'=>$profile['address'],
            'name'=>$profile['name'],
            'grade'=>$profile['grade'],
            'birthday'=>$profile['birthday'],
            'work_address'=>$work_address,
        	'thumbnail_image_url'=>$thu_img,
        	'source_image_url'=>$profile['image'],
        	'base_ver'=>$base_ver,
        	'vip_code'=>$profile['vip_code'],
        	'qc_code'=>$http.$_SERVER['HTTP_HOST'].'//upload/qccode/'.$user_id.'_qccode.png',
        );
        return $this->respond($this->format($params));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 更新用户基本信息
     **************/
    public function UpdateProfile(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'sex_id' => 'string',
            'live_place' => 'string',
            'name' => 'string',
        	'birthday'=> 'string',
        	'work_address'=> 'string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        /*$input = Input::except('ss','os_type','version');*/
        $params = array(
            'sex'=>$request->sex_id,
            'address'=>$request->live_place,
            'name'=>$request->name,
        	'birthday'=>$request->birthday,
        	'work_address'=>$request->work_address,
            'updated_at'=>date('Y-m-d H:i:s',time()),
        );
        $res = Member::where('user_id',$user_id)->update($params);
        $res_version = UserVersionInfoModel::where('user_id',$user_id)->first();
        if ($res_version){//如果用户的版本信息存在
            $version = array(
                'base_ver' => $res_version['base_ver']+1,
                'last_update_date' => date('Y-m-d H:i:s',time())
            );
            UserVersionInfoModel::where('user_id',$user_id)->update($version);
        }else{//如果用户的版本信息不存在
            $version = array(
                'base_ver' => 2, //原本没有版本信息，更新后插入该用户的版本信息，从2开始
                'user_id' => $user_id,
                'last_update_date' => date('Y-m-d H:i:s',time())
            );
            UserVersionInfoModel::insert($version);
        }
        if($res === false){
            return $this->setStatusCode(9998)->respondWithError($this->message);

        }else{
            return $this->respond($this->format('',true));
        }
    }

}
