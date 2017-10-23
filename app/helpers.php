<?php


// /**
//  * 给用户分配服务器并返回
//  * @param $uid
//  */
// function getDispatchServerInfo()
// {
//     if(\Cache::has(strtoupper('server-info'))){
//         return  \Cache::get(strtoupper('server-info'));
//     }

//     $lpsSql = <<<T_ECHO
// SELECT ip,port
// FROM dim_server_config
// WHERE category = 2 AND is_enable = 1 AND  (max_loading - curr_loading) > 0
// ORDER BY max_loading - curr_loading DESC
// T_ECHO;

//     $mecData = \DB::table('dim_server_config')
//         ->where('category', 1)
//         ->where('is_enable', 1)->first(['local_ip', 'local_port']);

//     $lpsData = \DB::select($lpsSql)[0];

//     $httpData = \DB::table('dim_server_config')
//         ->where('category', 3)
//         ->where('is_enable', 1)->first(['ip', 'port']);

//     $newsData = \DB::table('dim_server_config')
//         ->where('category', 4)
//         ->where('is_enable', 1)->first(['ip', 'port']);

//     $fileData = \DB::table('dim_server_config')
//         ->where('category', 5)
//         ->where('is_enable', 1)->first(['ip', 'port']);

//     if ($mecData && $lpsData && $httpData && $newsData && $fileData) {

//         $serverInfo = [
//             "mec_ip" => $mecData->local_ip,
//             "mec_port" => $mecData->local_port,
//             "lps_ip" => $lpsData->ip,
//             "lps_port" => $lpsData->port,
//             "api_ip" => $httpData->ip,
//             "api_port" => $httpData->port,
//             "news_ip" => $newsData->ip,
//             "news_port" => $newsData->port,
//             "file_ip" => $fileData->ip,
//             "file_port" => $fileData->port
//         ];

//         \Cache::add(strtoupper('server-info'),$serverInfo,15);

//         return $serverInfo;

//     }

//     return false;

// }


/**随机数生成方法
 * @param $len
 * @param string $type
 * @return string
 */
function getRandomID($len, $type = "1")
{
    $numArr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $upperArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $lowerArr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    $randStr = "";
    if ($type == "1")
        $randArr = array_merge($numArr, $upperArr, $lowerArr);
    else if ($type == "2")
        $randArr = array_merge($upperArr, $lowerArr);
    else if ($type == "3")
        $randArr = $numArr;
    $cnt = count($randArr);
    for ($i = 0; $i < $len; $i++)
        $randStr .= $randArr[mt_rand(0, $cnt - 1)];
    return $randStr;
}


function mobileFilter($mobileList)
{
    $data = [];

    $mobileArr = explode(',',$mobileList);

    return array_map(function ($mobile) use($data) {
        if (preg_match("/^1[34578][0-9]{9}$/",$mobile)) {
            $data[] = $mobile;
        }
        return $data;
    },$mobileArr);

}

function getUserInfoByUid($idList)
{
    if (!is_array($idList))
        $idList = explode(',',$idList);

    $usersInfo =  DB::table(App\Session::$sendTableName.' as s')
        ->select('s.user_id','s.client_type','s.mid','s.push_service_type','s.mec_ip','s.mec_port','s.lps_ip','s.lps_port','b.mobile')
        ->leftJoin('sky_user_data_master.user_base_info as b', 's.user_id', '=', 'b.user_id')
        ->whereIn('s.user_id', $idList)
        ->get();
//
//
//    $usersInfo =  DB::table('user_session_info as s')
//        ->select('s.user_id','s.client_type','s.mid','s.push_service_type','s.mec_ip','s.mec_port','s.lps_ip','s.lps_port','b.mobile')
//        ->leftJoin('sky_user_data_master.user_base_info as b', 's.user_id', '=', 'b.user_id')
//        ->whereIn('s.user_id', $idList)
//        ->get();



    if($usersInfo){
        return  array_map(function ($userInfo) {
            return array(
                'user_id' => (string)$userInfo->user_id,
                'client_type' => (string)$userInfo->client_type,
                'mid' => (string)$userInfo->mid,
                'push_service_type' => $userInfo->push_service_type,
                'mec_ip' => (string)$userInfo->mec_ip,
                'mec_port' => (string)$userInfo->mec_port,
                'lps_ip' => (string)$userInfo->lps_ip,
                'lps_port' => (string)$userInfo->lps_port,
                'mobile' => (string)$userInfo->mobile,
            );
        },$usersInfo);
    }


}

function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6367000;

    $lat1 = ($lat1 * pi() ) / 180;
    $lng1 = ($lng1 * pi() ) / 180;

    $lat2 = ($lat2 * pi() ) / 180;
    $lng2 = ($lng2 * pi() ) / 180;

    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;

    return round($calculatedDistance);
}


function  generatorOrderId()
{
    $time = explode(' ', microtime());
    $num = getRandomID(4,3);
    return rand(1, 9) .$num.intval(($time[0] + $time[1]) * 100) . rand(0, 9);
}

//二维数组去重
function unique_arr($array2D,$stkeep=false,$ndformat=true)
{
    $output = [];
    // 判断是否保留一级数组键 (一级数组键可以为非数字)
    if($stkeep) $stArr = array_keys($array2D);

    // 判断是否保留二级数组键 (所有二级数组键必须相同)
    if($ndformat) $ndArr = array_keys(end($array2D));

    //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
    foreach ($array2D as $v){
        $v = join(",",$v);
        $temp[] = $v;
    }

    //去掉重复的字符串,也就是重复的一维数组
    $temp = array_unique($temp);

    //再将拆开的数组重新组装
    foreach ($temp as $k => $v)
    {
        if($stkeep) $k = $stArr[$k];
        if($ndformat)
        {
            $tempArr = explode(",",$v);
            foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
        }
        else $output[$k] = explode(",",$v);
    }

    return $output;
}



/**
 * 验证规则合并处理并返回新规则
 * @param $arr1
 * @param $arr2
 * @return array
 */
function  array_validate_addition($arr1,$arr2)
{
    //处理多个键值映射
    $newArr=[];
    foreach($arr2 as $key=>$value){
        if(strpos($key,',')) {
            $elements = explode(',', $key);
            foreach($elements as $element){
                $newArr[$element]=$value;
            }
        }else{
            $newArr[$key]=$value;
        }
    }
    //合并规则
    $arr=array_merge_recursive($arr1,$newArr);
    foreach($arr as $k=>$v){
        if(is_array($v)){
            //是否为重写
            if(array_key_exists('__rewrite',$v)){
                $arr[$k]=$v[1];
            }else{
                //追加
                $v[1]=='required' ? $arr[$k]=$v[1].'|'.$v[0] : $arr[$k]=$v[0].'|'.$v[1];
            }
        }
    }
    return $arr;

}

//获取用户信息(姓名，年龄，性别，头像地址，)
function getUserInfoById($uid){
	$sexArr=array(
		1=>'男',
		2=>'女',
		0=>'未选择',
	);
	$userInfo=\App\Base::where('user_id',$uid)->with('version')->with('userExtend')->first()->toArray();

	$age=date('Y-m-d',time())-date('Y-m-d',strtotime($userInfo['user_extend']['birthday']));

	$result['user_name']=$userInfo['user_name'];
	$result['thumbnail_image_url']=$userInfo['version']['thumbnail_image_url'];
	$result['source_image_url']=$userInfo['version']['source_image_url'];
	$result['sex']=$sexArr[$userInfo['user_extend']['sex_id']];
	$result['age']=$age;
	
	return $result;	
}
//取得诊所信息
function getClinicInfo($id){
	
	$clinic=\App\UserBespeak::where('cisi_id',$id)->first()->clinicService->clinic->toArray();
	$result['clinic_id']=$clinic['id'];
	$result['clinic_name']=$clinic['name'];
	$result['logo_url']=$clinic['logo_url'];
	$result['address']=$clinic['address'];
	$result['province']=$clinic['province'];
	$result['city']=$clinic['city'];
	$result['area']=$clinic['area'];
	
	return $result;
}



/**
 * 根据奖励规则返回要奖励k币
 * @param $rule
 * @param $money
 * @return float|int|string
 */
function getRightRule($rule,$money){
    $k=0;
    foreach($rule as $v){
        if($money>=$v[0] and $money<=$v[1]){
            $k=ceil($money*$v[2]);
            break;
        }

    }

    return $k;
}

/**
 * 生成数字验证码
 * @param int $num 验证码长度
 *
 */
function make_pin_code($num=6){
    $code='';
    for($i=0;$i<$num;$i++){
        $code.= mt_rand(0,9);
    }

    return $code;
}

/**send_sms 发送短信
 *参数：$mobile 发送目标号码
 * 参数：$send_type 消息模板序列号 在消息模板语言包中定义
 *返回值：发送成功返回1
 */
function send_sms_ex($receiver,$send_type,$code)
{

    $message = trans('clinic.msg_template.'.$send_type,['code'=>$code]);
    $params = array(
        "cdkey" => config('clinic-config.SMS_API_USER'),
        "password" => config('clinic-config.SMS_API_PWD'),
        "phone" => $receiver,
        "message" => $message,
    );

    $client=new \Guzzle\Http\Client();

    $res=$client->post(config('clinic-config.SMS_API_PATH'),null,$params);

    $result=$res->send();

    $httpResult=$result->getBody(true);

    if(null == $httpResult){
        return 0;
    }
    $str = "/<error>(.*)<\/error>/";
    $array = array();
    if(preg_match($str,$httpResult,$array)){
        $error_code = $array[1];
    }
    if($error_code == "0")
        return 1;
    else
        return 0;
}
//上传图片
function uploadPic($file){
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


		if (!file_exists(base_path('storage').'/upload/hospital/')){
				mkdir(base_path('storage').'/upload/hospital/');
		}
		$a=$file->move(base_path('storage').'/upload/hospital/',$new_name);
		Image::make(base_path('storage').'/upload/hospital/'.$new_name)->resize(100, 100)->save(base_path('storage').'/upload/hospital/'.'thu_'.$new_name);
		$name='/storage/upload/hospital/'.$new_name;
		return $name;
}
//获取周围坐标
function returnSquarePoint($lng, $lat,$distance = 0.5){
	$earthRadius = 6378138;
	$dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);
	$dlat = $distance/$earthRadius;
	$dlat = rad2deg($dlat);
	return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
	);
}
function returnImage($path){
	$url=base_path().$path;
	return response()->download($url,null,[],null);	
}

function sendSms($mobile,$content){
	// 发送单条短信
	$smsOperator = new \SmsOperator();
	$data['mobile'] = $mobile;
	$data['text'] = '【圣特佳100】'.$content;
	$result = $smsOperator->single_send($data);
	//dd($result);
	return $result;
}
function formatContent($content){	
	$url='https://'.$_SERVER['HTTP_HOST'].'/storage/upload/';
	$new_content=str_replace("/storage/upload/",$url,$content);	
	$new_content=' <style> *{word-break:break-all;}</style> '.$new_content;
	$style='<img style="max-width:100%"';
	return str_replace("<img",$style,$new_content);
	
}
function createPassword(){
$string = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
$str = '';
for ($i=0; $i < 10; $i++) { 
    $str.= $string[rand(0,strlen($string)-1)];
}
return $str;

}
