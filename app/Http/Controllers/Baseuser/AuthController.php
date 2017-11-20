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
//use App\Http\Controllers\Weixin;



use Carbon\Carbon;
use App\Session;
use Acme\Exceptions\ValidationErrorException;
use Acme\Transformers\UserBaseTransformer;
use Acme\Transformers\UserVerTransformer;
use Acme\Transformers\UserExtendTransformer;
use App\TraitCollections\ServerTrait;
use App\TraitCollections\CurlHttpTrait;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
     * 注册
     **************/
    public function register(Request $request)
    {

        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pw' => 'required|min:8|max:20',
            'name' => 'string',
            'sex' => 'integer|in:1,2', //1男2女0未选择
            'pin' => 'required',
            'invite_id'=>'string' //非必填，邀请人id
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        //首先检测该手机号码是否存在
        $is_exist = \DB::table('ys_member')->where('mobile',$request->un)->first();
        if(!empty($is_exist)){ //表示该用户已经存在
            return $this->setStatusCode(1002)->respondWithError($this->message);
        }

        $max = UserPincodeHistoryModel::where('mobile',$request->un)->where('service_type',1)->orderBy('id','desc')->first();//获取id最大值
        if(empty($max) || ($max->pin_code != $request->pin)){ //如果为空或者验证码不一致，则报错，提示验证码错误
            return $this->setStatusCode(1007)->respondWithError($this->message);

        }

        //进行验证码校验，如果验证码超过10分钟，那么则失效了，其次，该验证码状态必须是未被验证状态，否则也视为失效
        $minute=floor((time()-strtotime($max->pin_made_time))%86400/60);
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        if(($minute>$userful_time) || ($max->is_succ != 0)){ //表示该验证码已经失效
            return $this->setStatusCode(1008)->respondWithError($this->message);
        }

        //检测邀请人是否存在，以及邀请人id是否真实有效
        if(!empty($request->invite_id)){
            $is_true = \DB::table('ys_member')->where('user_id',$request->invite_id)->first();
            $invite_id  = empty($is_true) ? "" : $request->invite_id;
        }else{
            $invite_id = "";
        }


        \DB::beginTransaction(); //开启事务

        //验证通过，则插入数据库，并且更改相应逻辑操作
        $insert = \DB::table('ys_member')->insert([

            'mobile' => $request->un,
            'password' => md5(md5($request->pw)),
            'created_at' => date('Y-m-d H:i:s'),
            'name' => empty($request->name) ? '游客' : $request->name,
            'sex' => empty($request->sex) ? '0' : $request->sex,
            'invite_id' => $invite_id,
        ]);

        $update =  UserPincodeHistoryModel::where('id',$max->id)->update([

             'is_succ' => '1',
             'pin_accmulation_time' =>\DB::Raw('now()')
        ]);

        if ($insert && $update) {
            DB::commit();
            return $this->respond($this->format([],true));
        }else {
            DB::rollBack();
            return $this->setStatusCode(1036)->respondWithError($this->message);
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
            'pushsvc' => 'integer|in:1,2',
            'ct' => 'required|integer|in:1,2,3' //1代表andorid、2代表IOS、3代表web
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        
        //手机号未注册，提示未注册
        $had_mobile=\DB::table('ys_member')->where('mobile',$request->un)->first();
        if(empty($had_mobile)){ //手机号码不存在
        	return $this->setStatusCode(1014)->respondWithError($this->message);        	 
        }

        //密码不正确
        if($had_mobile->password != md5(md5($request->pw))){
        	return $this->setStatusCode(1010)->respondWithError($this->message);        	 
        }
        $client_type = $request->get('ct');
		$data=array(
					'user_name'=>$had_mobile->name,
					'user_id'=>$had_mobile->user_id,
					'mobile'=>$had_mobile->mobile,
					
					);
        if($client_type==3){
        	$push_service = '';
        }else{
        	$push_service = $request->get('pushsvc');
        	$mid = $request->get('mid');
        	if ($client_type==2){  //add  2014 09 18   by song
        		$oldSession=Session::where('mid',$mid)->first();
        		if (!is_null($oldSession) && $oldSession['user_id']!=$had_mobile->user_id){
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
        $serverArr = $this->getDispatchServerInfo('ys');
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

    //免密登陆
    public function postLoginByMobile(Request $request){


        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'pin' => 'required|string', //短信验证码
            'mid' => 'string',
            'pushsvc' => 'integer|in:1,2',
            'ct' => 'required|integer|in:1,2,3' //1代表andorid、2代表IOS、3代表web
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        //手机号未注册，提示未注册
        $had_mobile=\DB::table('ys_member')->where('mobile',$request->un)->first();
        if(empty($had_mobile)){ //手机号码不存在
            return $this->setStatusCode(1014)->respondWithError($this->message);
        }

        //手机验证码的验证
        $max = UserPincodeHistoryModel::where('mobile',$request->un)->where('service_type',4)->orderBy('id','desc')->first();//获取id最大值
        if(empty($max) || ($max->pin_code != $request->pin)){ //如果为空或者验证码不一致，则报错，提示验证码错误
            return $this->setStatusCode(1007)->respondWithError($this->message);
        }
        //进行验证码校验，如果验证码超过10分钟，那么则失效了，其次，该验证码状态必须是未被验证状态，否则也视为失效
        $minute=floor((time()-strtotime($max->pin_made_time))%86400/60);
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        if(($minute>$userful_time) || ($max->is_succ != 0)){ //表示该验证码已经失效
            return $this->setStatusCode(1008)->respondWithError($this->message);
        }
         //更新验证码状态
         UserPincodeHistoryModel::where('id',$max->id)->update([
            'is_succ' => '1',
            'pin_accmulation_time' =>\DB::Raw('now()')
        ]);

        $client_type = $request->get('ct');
        $data=array(
            'user_name'=>$had_mobile->name,
            'user_id'=>$had_mobile->user_id,
            'mobile'=>$had_mobile->mobile,

        );
        if($client_type==3){
            $push_service = '';
        }else{
            $push_service = $request->get('pushsvc');
            $mid = $request->get('mid');
            if ($client_type==2){  //add  2014 09 18   by song
                $oldSession=Session::where('mid',$mid)->first();
                if (!is_null($oldSession) && $oldSession['user_id']!=$had_mobile->user_id){
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
        $serverArr = $this->getDispatchServerInfo('ys');
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
            $serverVer=\DB::table('ys_updateinfo')
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


        //手机号未注册，提示未注册
        $had_mobile=\DB::table('ys_member')->where('mobile',$request->un)->first();
        if(empty($had_mobile)){ //手机号码不存在
            return $this->setStatusCode(1014)->respondWithError($this->message);
        }

        //手机验证码的验证
        $max = UserPincodeHistoryModel::where('mobile',$request->un)->where('service_type',3)->orderBy('id','desc')->first();//获取id最大值
        if(empty($max) || ($max->pin_code != $request->pin)){ //如果为空或者验证码不一致，则报错，提示验证码错误
            return $this->setStatusCode(1007)->respondWithError($this->message);
        }
        //进行验证码校验，如果验证码超过10分钟，那么则失效了，其次，该验证码状态必须是未被验证状态，否则也视为失效
        $minute=floor((time()-strtotime($max->pin_made_time))%86400/60);
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        if(($minute>$userful_time) || ($max->is_succ != 0)){ //表示该验证码已经失效
            return $this->setStatusCode(1008)->respondWithError($this->message);
        }


        \DB::beginTransaction(); //开启事务

        //验证通过，则更改密码
        $update1 = \DB::table('ys_member')->where('mobile',$request->un)->update([
            'password' => md5(md5($request->pw)),

        ]);

        $update2 =  UserPincodeHistoryModel::where('id',$max->id)->update([

            'is_succ' => '1',
            'pin_accmulation_time' =>\DB::Raw('now()')
        ]);

        if ($update1 && $update2) {
            DB::commit();
            return $this->respond($this->format([],true));
        }else {
            DB::rollBack();
            return $this->setStatusCode(1036)->respondWithError($this->message);
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
            'ss' => 'required|string',
            'npw' => 'required|min:8|max:20',
            'opw' => 'required|string',
        ])
            ->_validate($request->all());

        if (!$validator) throw new ValidationErrorException;
        
    	$user_id = $this->getUserIdBySession($request->ss); //获取用户id		
        if(!$user_id){
        	return $this->setStatusCode(1011)->respondWithError($this->message);        	
        }
        $had=\DB::table('ys_member')->where('user_id',$user_id)->where('password',md5(md5($request->opw)))->first();
        if(empty($had)){
        	return $this->setStatusCode(6102)->respondWithError($this->message);
        }
        $data=array(
        	'password'=>md5(md5($request->npw)),
        );
        $res=\DB::table('ys_member')->where('user_id',$user_id)->update($data);

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

        $http = getenv('HTTP_REQUEST_URL'); //获取域名

        if (isset($request->uid)){//uid存在，获取他人头像
            $uid = \DB::table('ys_member')->where('user_id',$request->uid)->first();
            if (empty($uid)){
                if (empty($uid->image)){//没有上传头像
                    $new_data['source_image_url']='';
                    $new_data['thumbnail_image_url']='';
                    return $this->respond($this->format($new_data));
                }else{
                    $new_data['source_image_url']=$http.'/api/gxsc/show-ico/'.$uid->image;
                    $new_data['thumbnail_image_url']=$http.'/api/gxsc/show-ico/'.'thu_'.$uid->image;
                    return $this->respond($this->format($new_data));
                }
            }else{
                return $this->setStatusCode(1039)->respondWithError($this->message);  //该用户不存在
            }
        }else{//uid不存在，获取用户自己的头像
            $res = \DB::table('ys_member')->where('user_id',$user_id)->first();
            if (empty($res->image)){//没有上传头像
                $new_data['source_image_url']='';
                $new_data['thumbnail_image_url']='';
                return $this->respond($this->format($new_data));

            }else{
                $new_data['source_image_url']=$http.'/api/gxsc/show-ico/'.$res->image;
                $new_data['thumbnail_image_url']=$http.'/api/gxsc/show-ico/'.'thu_'.$res->image;
                return $this->respond($this->format($new_data));
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

        $http = getenv('HTTP_REQUEST_URL'); //获取域名

        //上传图片
        if($request->hasFile('image')){//判断是否有图片上传
            $up_res=$this->uploadPicHandle($request->file('image'));//上传图片
            if($up_res){

                //如果存在，则更新数据库
                 $res = \DB::table('ys_member')->where('user_id',$user_id)->update(['image'=>$up_res]);

                 if($res){

                     $new_data['source_image_url']=$http.'/api/gxsc/show-ico/'.$up_res;
                     $new_data['thumbnail_image_url']=$http.'/api/gxsc/show-ico/'.'thu_'.$up_res;
                     return $this->respond($this->format($new_data));

                 }else{//更改图像失败
                     return $this->setStatusCode(6043)->respondWithError($this->message);
                 }
            }else{ //更改图像失败
                return $this->setStatusCode(6043)->respondWithError($this->message);
            }
        }else{ //未找到图片
            return $this->setStatusCode(6043)->respondWithError($this->message);
        }

    }

    //图片视图
    public function showIco($fileName)
    {
        return \Response::download(storage_path().'/app/avatars/'.$fileName,null,array(),null);
    }



    private function uploadPicHandle($file){

        $filesize=$file->getClientSize();
        if($filesize>2097152){
            die("{code': 6044,'msg':'文件超过2MB'}");
            return false;
        }

        //检查文件类型
        $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.

        if($entension=='imgj'){
            $new_entension='jpg';
        }elseif($entension=='imgp'){
            $new_entension='png';
        }elseif($entension=='imgg'){
            $new_entension='gif';
        }else{
            $new_entension=$entension;
        }
        $mimeTye = $file -> getMimeType();
        if(!in_array($mimeTye,array('image/jpeg','image/png'))){
            die("{code': 6045,'msg':'文件类型不允许'}");
            return false;
        }
        $new_name=time().rand(100,999).'.'.$new_entension;

        $file->move(storage_path('app/avatars/'),$new_name);
        Image::make(storage_path('app/avatars/').$new_name)->resize(100, 100)->save(storage_path('app/avatars/').'thu_'.$new_name);

        return $new_name;
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

        $http = getenv('HTTP_REQUEST_URL'); //获取域名
        $profile = \DB::table('ys_member')->where('user_id',$user_id)->first();

        if(empty($profile)){ //用户不存在
            return $this->setStatusCode(1039)->respondWithError($this->message);
        }

        //接着检查是否是员工
        $is_employee = \App\EmployeeModel::where('user_id',$user_id)->first();
        $member = empty($is_employee) ? 0 : 1; //0会员  1员工


        //查一下昨日返利的金额
        $date = date("Y-m-d",strtotime("-1 day"));

        $res = \DB::table('ys_bills')->where('user_id',$user_id)->where('created_at','like','%'.$date.'%')->whereIn('type',[1,2,3])->get();

        if(empty($res)){
            $return_money = 0;
        }else{
            $return_money = 0;
            foreach($res as $k=>$v){
                $return_money += $v->amount;
            }
        }

        $params = [
            'user_id'=>	$user_id,
            'mobile'=>$profile->mobile,
            'sex_id'=>$profile->sex,
            'address'=>$profile->address,
            'name'=>$profile->name,
            'birthday'=>substr($profile->birthday,0,10),
            'thumbnail_image_url'=>empty($profile->image)? "" : $http.'/api/gxsc/show-ico/'.'thu_'.$profile->image,
            'source_image_url'=>empty($profile->image)? "" : $http.'/api/gxsc/show-ico/'.$profile->image,
            'is_member'=>$member,
            'balance'=>is_null($profile->balance) ? 0 : $profile->balance, //余额
            'yesterday_return_money'=>$return_money //昨日返利
        ];
        return $this->respond($this->format($params));
//
//        $profile = \DB::table('ys_member')->where('user_id',$user_id)->first();
//        $res_version = UserVersionInfoModel::where('user_id',$user_id)->first();
//        if ($res_version){
//            $base_ver = $res_version['base_ver'];
//        }else{
//            $base_ver = 1;
//        }
//
//        //缩略图
//        if($profile['image'] !=''){
//        	$img_arr=explode('/hospital/', $profile['image']);
//        	$thu_img=$img_arr[0].'/hospital/thu_'.$img_arr[1];
//        }else{
//        	$thu_img='';
//        }
//
//        if(!file_exists(public_path('/upload/qccode/').$user_id.'_qccode.png')){
//			//dd(public_path('upload/qccode/stj-icon.png'));
//        	//为用户生成邀请二维码图片
//        	\QrCode::format('png')->size(200)->errorCorrection('Q')->margin(0)->merge('/public/upload/qccode/stj-icon.png', .2)->generate(env('APPLY_URL').'?qc='.$profile['mobile'],public_path('/upload/qccode/').$user_id.'_qccode.png');
//
//        }
//        if ($profile['work_address'] == ""){
//            $work_address = '';
//        }else{
//            $work_address = $profile['work_address'];
//        }
//
//
//
//        if($_SERVER['SERVER_PORT'] == 443){
//        	$http='https://';
//        }else{
//        	$http='http://';
//        }
//
//
//        $params[] = array(
//        	'user_id'=>	$user_id,
//            'mobile'=>$profile['mobile'],
//            'sex_id'=>$profile['sex'],
//            'live_place'=>$profile['address'],
//            'name'=>$profile['name'],
//            'grade'=>$profile['grade'],
//            'birthday'=>$profile['birthday'],
//            'work_address'=>$work_address,
//        	'thumbnail_image_url'=>$thu_img,
//        	'source_image_url'=>$profile['image'],
//        	'base_ver'=>$base_ver,
//        	'vip_code'=>$profile['vip_code'],
//        	'qc_code'=>$http.$_SERVER['HTTP_HOST'].'//upload/qccode/'.$user_id.'_qccode.png',
//        );
//        return $this->respond($this->format($params));
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
            'sex' => 'integer|in:1,2', // 1男  2 女
            'address' => 'string',
            'name' => 'string',
        	'birthday'=> [
                'string',
                'regex:/^(19|20)\d{2}-(1[0-2]|0?[1-9])-(0?[1-9]|[1-2][0-9]|3[0-1])$/'
            ],
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $tmp = [];
        $params_arr = ["sex","address","name","birthday"];
        foreach($request->all() as $k=>$v){
            if(in_array($k,$params_arr)){
                  $tmp[$k] = $v;
            }
        }
        if(empty($tmp)){ //没有要更新的内容 1049
            return $this->setStatusCode(1049)->respondWithError($this->message);
        }

        $res = \DB::table('ys_member')->where('user_id',$user_id)->update($tmp);
        if($res === false){
            return $this->setStatusCode(9998)->respondWithError($this->message);

        }else{
            return $this->respond($this->format([],true));
        }

//        $params = array(
//            'sex'=>$request->sex_id,
//            'address'=>$request->live_place,
//            'name'=>$request->name,
//        	'birthday'=>$request->birthday,
//            'updated_at'=>date('Y-m-d H:i:s',time()),
//        );
//        $res = Member::where('user_id',$user_id)->update($params);
//        $res_version = UserVersionInfoModel::where('user_id',$user_id)->first();
//        if ($res_version){//如果用户的版本信息存在
//            $version = array(
//                'base_ver' => $res_version['base_ver']+1,
//                'last_update_date' => date('Y-m-d H:i:s',time())
//            );
//            UserVersionInfoModel::where('user_id',$user_id)->update($version);
//        }else{//如果用户的版本信息不存在
//            $version = array(
//                'base_ver' => 2, //原本没有版本信息，更新后插入该用户的版本信息，从2开始
//                'user_id' => $user_id,
//                'last_update_date' => date('Y-m-d H:i:s',time())
//            );
//            UserVersionInfoModel::insert($version);
//        }
//        if($res === false){
//            return $this->setStatusCode(9998)->respondWithError($this->message);
//
//        }else{
//            return $this->respond($this->format('',true));
//        }
    }



    //绑定openId
    public function bindOpenId(Request $request){

        $validator = $this->setRules([
            'un'  => 'required|regex:/^1[34578][0-9]{9}$/',
            'pin' => 'required|string',
            'openId' => 'required|string',
            'invite_id'=>'string' //邀请人的id，非必填

        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;

        //手机验证码的验证
        $max = UserPincodeHistoryModel::where('mobile',$request->un)->where('service_type',5)->orderBy('id','desc')->first();//获取id最大值
        if(empty($max) || ($max->pin_code != $request->pin)){ //如果为空或者验证码不一致，则报错，提示验证码错误
            return $this->setStatusCode(1007)->respondWithError($this->message);
        }
        //进行验证码校验，如果验证码超过10分钟，那么则失效了，其次，该验证码状态必须是未被验证状态，否则也视为失效
        $minute=floor((time()-strtotime($max->pin_made_time))%86400/60);
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        if(($minute>$userful_time) || ($max->is_succ != 0)){ //表示该验证码已经失效
            return $this->setStatusCode(1008)->respondWithError($this->message);
        }

        //首先判断该用户是否是系统内用户
        $had_mobile=\DB::table('ys_member')->where('mobile',$request->un)->first();

        //获取分配服务器信息
        $serverArr = $this->getDispatchServerInfo('ys');

        if(empty($had_mobile)){ //手机号码不存在，则应该为其注册

            //检测邀请人是否存在，以及邀请人id是否真实有效
            if(!empty($request->invite_id)){
                $is_true = \DB::table('ys_member')->where('user_id',$request->invite_id)->first();
                $invite_id  = empty($is_true) ? "" : $request->invite_id;
            }else{
                $invite_id = "";
            }

            /**
             * 这里请求微信服务器，获取用户头像和姓名，然后把头像下载下来放到本地服务器
             */
             $weixin_info = $this->getWeiXin($request->openId);

die('11111111');
            \DB::beginTransaction(); //开启事务

            //验证通过，则插入数据库，并且更改相应逻辑操作
            $insert1 = \DB::table('ys_member')->insertGetId([

                'mobile' => $request->un,
                'password' => md5(md5('123456789')),
                'created_at' => date('Y-m-d H:i:s'),
                'name' => '游客',
                'sex' =>0,
                'invite_id' => $invite_id,
            ]);


            $update1 =  UserPincodeHistoryModel::where('id',$max->id)->update([

                'is_succ' => '1',
                'pin_accmulation_time' =>\DB::Raw('now()')
            ]);

            $insert2 = \DB::table('ys_session_info')->insert([

                'user_id'=>$insert1,
                'client_type'=>1, //安卓
                'session'=>'',
                'mid'=>$insert1,
                'push_service_type'=>2,
                'mec_ip' => $serverArr['mec_ip'],
                'mec_port' => $serverArr['mec_port'],
                'lps_ip' => $serverArr['lps_ip'],
                'lps_port' => $serverArr['lps_port'],
                'last_get_session_date' => Carbon::now(),
                'session_hash' => '',
                'openId'=>$request->openId,
            ]);

            if ($insert1 && $update1 && $insert2) {
                DB::commit();
                return $this->respond($this->format([],true));
            }else {
                DB::rollBack();
                return $this->setStatusCode(1040)->respondWithError($this->message);
            }


        }else{ //否则，直接绑定openId即可


            die('22222');

            $session = (new Session)->createSession($had_mobile->user_id);
            $is_exist = \DB::table('ys_session_info')->where('user_id',$had_mobile->user_id)->first();

            \DB::beginTransaction(); //开启事务
            if(!empty($is_exist)){
                $update1 = \DB::table('ys_session_info')->where('user_id',$had_mobile->user_id)->update(['openId'=>$request->openId]);
            }else{

                $update1 = \DB::table('ys_session_info')->insert([

                                'user_id'=>$had_mobile->user_id,
                                'client_type'=>1, //安卓
                                'session'=>$session,
                                'mid'=>$had_mobile->user_id,
                                'push_service_type'=>2,
                                'mec_ip' => $serverArr['mec_ip'],
                                'mec_port' => $serverArr['mec_port'],
                                'lps_ip' => $serverArr['lps_ip'],
                                'lps_port' => $serverArr['lps_port'],
                                'last_get_session_date' => Carbon::now(),
                                'session_hash' => abs(crc32($session)),
                                'openId'=>$request->openId,
                            ]);
            }

            $update2 =  UserPincodeHistoryModel::where('id',$max->id)->update([

                'is_succ' => '1',
                'pin_accmulation_time' =>\DB::Raw('now()')
            ]);

            if ($update1 && $update2) {
                DB::commit();
                return $this->respond($this->format([],true));
            }else {
                DB::rollBack();
                return $this->setStatusCode(1040)->respondWithError($this->message);
            }

        }

    }


    private function getWeiXin($openId){

        $appId = getenv('appId');
        $appSecret = getenv('appSecret');

        $jssdk = new \App\Http\Controllers\Weixin\JSSDK($appId,$appSecret);

        $data = $jssdk->getUserInfo($openId);

        print_r($data);die();



    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 重置密码前发送短信
     **************/
//    public function resetPasswordBySMS(Request $request)
//    {
//
//        $validator = $this->setRules([
//            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
//           // 'service_type' => 'required'//1:注册3：手机找回密码
//        ])
//            ->_validate($request->only('un'));
//        if (!$validator) throw new ValidationErrorException;
//
//        $user = Member::where('mobile',$request->un)->first();
//        if ($user || $request->type == '1'){//如果用户存在，或者type=1代表从注册前发短信跳转过来
//            $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get();
//            $max = $res_un[count($res_un)-1]['pin_made_time'];//最后一条验证码创建时间
//            $interval_f = date('Y-m-d H:i:s',strtotime("$max+5 minute"));//间隔时间
//            $datetime = date('Y-m-d H:i:s',time());//当前时间
//            if (count($res_un)>='6'){//如果该手机号码申请的注册码超过六条
//                $min = $res_un[count($res_un)-'6']['pin_made_time'];//倒数第六条验证码创建时间
//                $time = date('Y-m-d 00:00:00');//当天时间零点
//                if ($min>=$time){//如果倒数第六条验证码时间，不小于当天时间零点，说明今天发满6条验证码
//                    $data['msg'] = '今天申请过6次验证码，请明天再试';
//                    return $this->respond($data);
//                }else{
//                    if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
//                        $pin = rand('100000','999999');
//                        sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//                        $params = array(
//                            'pin_code'=>$pin,
//                            'mobile'=>$request->un,
//                            'service_type'=>'3',
//                            'pin_made_time'=>date('Y-m-d H:i:s',time()),
//                        );
//                        $res = UserPincodeHistoryModel::insert($params);
//                        $data['msg'] = '您的验证码是:'.$pin;
//                        if($res){
//                            return $this->respond($this->format($data));
//                        }else{
//                            return $this->setStatusCode(9998)->respondWithError($this->message);
//                        }
//                    }else{
//                        $data['msg'] = '5分钟之内已经产生过一次验证码';
//                        return $this->respond($data);
//                    }
//                }
//            }else{//该手机号申请验证码不超过6条
//                if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
//                    $pin = rand('100000','999999');
//                    sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//                    $params = array(
//                        'pin_code'=>$pin,
//                        'mobile'=>$request->un,
//                        'service_type'=>'3',
//                        'pin_made_time'=>date('Y-m-d H:i:s',time()),
//                    );
//                    $res = UserPincodeHistoryModel::insert($params);
//                    if($res){
//                        $data['msg'] = '短信已发送:'.$pin;
//                        return $this->respond($this->format($data));
//                    }else{
//                        return $this->setStatusCode(9998)->respondWithError($this->message);
//                    }
//                }else{
//                    $data['msg'] = '5分钟之内已经产生过一次验证码';
//                    return $this->respond($data);
//                }
//            }
//        }else{
//         return $this->setStatusCode(1014)->respondWithError($this->message);
//        }
//    }






    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 重发短信
     ***************/
//    public function sendSMSAgain(Request $request)
//    {
//
//        $validator = $this->setRules([
//            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
//            'service_type' => 'integer'
//        ])
//            ->_validate($request->all());
//        if (!$validator) throw new ValidationErrorException;
//
//        $user = Member::where('mobile',$request->un)->first();
//        $is_sicc = UserPincodeHistoryModel::where('is_succ','1')->first();
//
//        if ($request->service_type == '1' || $request->service_type ==''){
//            $service_type='1';
//            if ($user){
//        		return $this->setStatusCode(1002)->respondWithError($this->message);
//            }
//        }else{
//        	if (!$user){
//        		return $this->setStatusCode(1014)->respondWithError($this->message);
//        	}
//            $service_type=3;
//        }
//        $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get();
//        if(count($res_un)>0){
//        	$max = $res_un[count($res_un)-1]['pin_made_time'];//最后一条验证码创建时间
//        	$interval_f = date('Y-m-d H:i:s',strtotime("$max+5 minute"));//间隔时间
//        	$datetime = date('Y-m-d H:i:s',time());//当前时间
//
//        	//如果该手机号码申请的注册码超过六条
//        	if (count($res_un)>='6'){
//        		$min = $res_un[count($res_un)-'6']['pin_made_time'];//倒数第六条验证码创建时间
//        		$time = date('Y-m-d 00:00:00');//当天时间零点
//        		//如果倒数第六条验证码时间，不小于当天时间零点，说明今天发满6条验证码
//        		if ($min>=$time){
//        			$data['msg'] = '今天申请过6次验证码，请明天再试';
//        			return $this->respond($data);
//        		}else{
//        			if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
//        				$pin = rand('100000','999999');
//        				sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//        				$params = array(
//        						'pin_code'=>$pin,
//        						'mobile'=>$request->un,
//        						'service_type'=>$service_type,
//        						'pin_made_time'=>date('Y-m-d H:i:s',time()),
//        				);
//        				$res = UserPincodeHistoryModel::insert($params);
//        				$data['msg'] = '短信已发送:'.$pin;
//        				if($res){
//        					return $this->respond($this->format($data));
//        				}else{
//        					return $this->setStatusCode(9998)->respondWithError($this->message);
//        				}
//        			}else{
//        				$data['msg'] = '5分钟之内已经产生过一次验证码';
//        				return $this->respond($data);
//        			}
//        		}
//        	}else{//该手机号申请验证码不超过6条
//        		if ($datetime>$interval_f){ //如果申请时间超过上一条5分钟
//        			$pin = rand('100000','999999');
//        			sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//        			$params = array(
//        					'pin_code'=>$pin,
//        					'mobile'=>$request->un,
//        					'service_type'=>$service_type,
//        					'pin_made_time'=>date('Y-m-d H:i:s',time()),
//        			);
//        			$res = UserPincodeHistoryModel::insert($params);
//        			$data['msg'] = '短信已发送:'.$pin;
//        			if($res){
//        				return $this->respond($this->format($data));
//        			}else{
//        				return $this->setStatusCode(9998)->respondWithError($this->message);
//        			}
//        		}else{
//        			$data['msg'] = '5分钟之内已经产生过一次验证码';
//        			return $this->respond($data);
//        		}
//        	}
//
//
//        }else{
//
//        	$pin = rand('100000','999999');
//        	sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//        	$params = array(
//        			'pin_code'=>$pin,
//        			'mobile'=>$request->un,
//        			'service_type'=>$service_type,
//        			'pin_made_time'=>date('Y-m-d H:i:s',time()),
//        	);
//        	$res = UserPincodeHistoryModel::insert($params);
//        	$data['msg'] = '短信已发送:'.$pin;
//        	if($res){
//        		return $this->respond($this->format($data));
//        	}else{
//        		return $this->setStatusCode(9998)->respondWithError($this->message);
//        	}
//
//
//
//        }
//
//
//    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationErrorException
     * 注册之前发送短信
     **************/

//    public function beforeRegisterSendSMS(Request $request)
//    {
//        $validator = $this->setRules([
//            'un' => 'required|regex:/^1[34578][0-9]{9}$/'
//        ])
//            ->_validate($request->only('un'));
//        if (!$validator) throw new ValidationErrorException;
//        $user = Member::where('mobile',$request->un)->first();
//        if (!$user){//如果用户不存在
//            $res_un = UserPincodeHistoryModel::where('mobile',$request->un)->get()->toArray();
//            if ($res_un){//如果该用户已申请过验证码
//                $res_id = UserPincodeHistoryModel::where('mobile',$request->un)->max('id');
//                $res_pin = UserPincodeHistoryModel::where('id',$res_id)->first();
//                $pin = $res_pin['original']['pin_code'];
//                sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//                $data['msg'] = '短信已发送'.$pin;
//                return $this->respond($this->format($data));
//            }else{
//                $pin = rand('100000','999999');
//                sendSms($request->un,'您的验证码是'.$pin.'');//发送短信
//                $params = array(
//                    'pin_code'=>$pin,
//                    'mobile'=>$request->un,
//                    'service_type'=>'1',
//                    'pin_made_time'=>date('Y-m-d H:i:s',time()),
//                );
//                $res = UserPincodeHistoryModel::insert($params);
//                $data['msg'] = '短信已发送:'.$pin;
//                if($res){
//                    return $this->respond($this->format($data));
//                }else{
//                    return $this->setStatusCode(9998)->respondWithError($this->message);
//                }
//            }
//        }else{
//            $data['msg'] = '用户已存在，直接登录';
//            return $this->respond($data);
//        }
//    }

}
