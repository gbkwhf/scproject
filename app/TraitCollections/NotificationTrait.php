<?php

namespace App\TraitCollections;

use Acme\Repository\MecManager;
use Acme\Repository\TcpConnection;
use App\ManageSession;
use App\Session;


trait NotificationTrait
{
    use HospitalRespondTrait;

    public function sendMessage($request,$body=null)
    {
        $user = $this->getMessageSenderInfo($request->get('ss'));
        $user_id = $user->user_id;
        $acceptorsInfo = $this->getAcceptorsInfo($request);
        if(!$acceptorsInfo) return $this->respondWithSendMessageError();


        //发送消息通知
        \Log::info($acceptorsInfo);

        $mecManager = new MecManager($user_id,$this->assembly($request),$acceptorsInfo);
        $mecManager->sendMessage();

        return $this->respondWithSendMessageSuccess();

    }

    private function getMessageSenderInfo($session, $fieldsArray = ['*'])
    {
        return  \DB::table('sky_user_data_master.user_base_info as b')
            ->leftJoin('ysbt_session_info as s','b.user_id','=','s.user_id')
            ->where('s.session', $session)
            ->first(['s.user_id','b.mobile']);

    }

    private function getAcceptorsInfo($request)
    {
        $acceptorIds = explode(',', $request->get('acceptor_ids'));
        $user = $this->getMessageSenderInfo($request->get('ss'));
        if(in_array($user->user_id,$acceptorIds)){
            $acceptors = array_flip($acceptorIds);           
            unset($acceptors[$user->user_id]);
            $acceptorIds = array_keys($acceptors);
        }       
        return $this->getUsersInfoForMessage($acceptorIds);

    }



    private function getUsersInfoForMessage(Array $ids,$fieldsArray = ['*'])
    {
        $userInfo = [];
        $users = \DB::table('sky_user_data_master.user_base_info as b')
                    ->leftJoin('ysbt_session_info as s','b.user_id','=','s.user_id')
                    ->whereIn('b.user_id',$ids)
                    ->get(['b.user_id','s.client_type','s.mid','s.push_service_type',
                        's.mec_ip','s.mec_port','s.lps_ip','s.lps_port','b.mobile']);
        foreach ($users as $key => $user){
                    $userInfo[$key]['user_id'] = (string)$user->user_id;
                    $userInfo[$key]['client_type'] = $user->client_type;
                    $userInfo[$key]['push_service_type'] = $user->push_service_type;
                    $userInfo[$key]['mid'] = $user->mid;
                    $userInfo[$key]['mec_ip'] = $user->mec_ip;
                    $userInfo[$key]['mec_port'] = (string)$user->mec_port;
                    $userInfo[$key]['lps_ip'] = $user->lps_ip;
                    $userInfo[$key]['lps_port'] = (string)$user->lps_port;
                    $userInfo[$key]['mobile'] = $user->mobile;
        }
        return $userInfo;
    }

    private function assembly($request)
    {

        $user = $this->getMessageSenderInfo($request->get('ss'));

        $userArr = [
            'src' => $user->user_id,
            'srcm' => $user->mobile,
        ];

        if(!$request->hasFile('file')){

            return  $userArr + [
                "id" => (string)$request->get('msg_id'),
                "type" => "MMS",
                "mime" => $request->get('mime'),
                "text" => rawurlencode(trim($request->get('text'))),
                "time" => (string)time()
            ];

        }
        //上传文件检查
        $file = $request->file('file');
        //检查文件上传过程中是否有错
        if($file->getError())
            return $this->respondWithParameterError();
        //检查文件是否超过上传文件配置的大小限制
        if ($file->getClientSize() > getenv('MAX_FILE_SIZE'))
            return $this->respondWithFileSizeError();
        //文件完整性检查
        if ($request->get('size') != $file->getClientSize())
            return $this->respondWithParameterError();

        //上传文件主体
        $saveFileResult = app('Acme\Repository\MMS_FileManager')->uploadFile($file->getRealPath(),$request->get('msg_id'),$request->all());

        if (!$saveFileResult) return $this->respondWithSysError();

        return $userArr + [
            "id" => (string)$request->get('msg_id'),
            "type" => "MMS",
            "mime" => $request->get('mime'),
            "filename" => rawurldecode($request->get('filename')),
            "size" => (string)$request->get('size'),
            "duration" => $request->has('duration')? (int)$request->get('duration') : 0,
            "time" => (string)time()
        ];

    }





    public function getOtherMessage($request)
    {

        $thumbnail = $request->get('thumbnail');

        $file_id = $request->get('msg_id');

        if($thumbnail) $file_id .= "-thumbnail";

        //查找文件ID对应的fastDFS的信息
        $getFileResult = app('Acme\Repository\MMS_FileManager')->downLoadFile($file_id);

        if(!$getFileResult)  return $this->respondWithFileNotExist();

        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".strlen($getFileResult));
        Header("Content-Disposition: attachment; filename=" . rand(123456789,999999999));

        echo $getFileResult;



    }


    public function sendMessagePublic($request,$body=[])
    {
    	\Log::info($request);
        $user = $this->getMessageSenderInfo($request->get('ss'));
        $user_id = $user->user_id;
        $acceptorsInfo = $this->getAcceptorsInfo($request);

        if(!$acceptorsInfo) return $this->respondWithSendMessageError();

        //发送消息通知
        \Log::info($acceptorsInfo);
        $userArr = [
            'src' => $user->user_id,
            'srcm' => $user->mobile,
            "time" => (string)time(),
        ];

        $mecManager = new MecManager($user_id,array_merge($userArr,$body),$acceptorsInfo);
        $res=$mecManager->sendMessage();
        \Log::info($res);
        return $res; 

    }



    public function getMessage($request)
    {


        $sessionTable =  'ysbt_session_info';
        
        $sessionInfo = \DB::table($sessionTable)
                        ->where('session',$request->get('ss'))
                        ->first();

        //更新当前用户最后一次取得消息的时间
        \DB::table($sessionTable)
            ->where('user_id', $sessionInfo->user_id)
            ->update(['last_get_msg_date' => date('Y-m-d H:i:s')]);


        $mec_ip = (string)$sessionInfo->mec_ip;
        $mec_port = (string)$sessionInfo->mec_port;
        $mid = (string)$sessionInfo->mid;

        //真实获取消息体结构
//        $contentBody = <<<E_ECHO
//0 SUCCESS
//msg=2,MMS,{"id":"25001579-1456455721-29","type":"MMS","mime":"text\/plain","srcm":"15529556060","src":"25035876","text":"123","time":1456455720}
//msg=3,MMS,{"id":"25001579-1456455721-30","type":"MMS","mime":"text\/plain","srcm":"15529556060","src":"25035876","text":"123","time":1456455720}
//msg=4,MMS,{"id":"25001579-1456455721-31","type":"MMS","mime":"text\/plain","srcm":"15529556060","src":"25035876","text":"123","time":1456455720}
//msg=5,MMS,{"id":"25001579-1456455721-32","type":"MMS","mime":"text\/plain","srcm":"15529556060","src":"25035876","text":"123","time":1456455720}
//E_ECHO;
        $result = (new TcpConnection($mec_ip,$mec_port))->tcpSend_ex("FETCH " . $mid . " \n");

        $data = [];

        $resArr = explode("\n", $result);
        $resCode = explode(" ",$resArr[0]);

        if($resCode[0] == "0" || $resCode[0] == "11002"){
            //过滤数值值为空的部分
            $resArr = array_values(collect($resArr)->filter(function($item){
                return $item;})->toArray()
            );
            for($i = 1 ; $i < count($resArr) ;$i++){
                $pos = strpos($resArr[$i],'{');
                $msgBeforeArr =  explode(',',substr($resArr[$i],0,$pos-1));
                //消息体ID和消息体类型
                $data[$i]['ack_id'] =  explode('=',$msgBeforeArr[0])[1];
                $data[$i]['type'] = $msgBeforeArr[1];
                //消息体内容
                $data[$i]['body'] = json_decode(substr($resArr[$i],$pos),true);

            }

            $data = array_values($data);

        }else{
            return $this->respondWithGetMessageError();
        }

        return $this->response($data);

    }
    
    public function ackMessage($request)
    {


        
        $sessionTable =  'ysbt_session_info';

        $sessionInfo = \DB::table($sessionTable)
            ->where('session',$request->get('ss'))
            ->first();

        $mec_ip = $sessionInfo->mec_ip;

        $mec_port = $sessionInfo->mec_port;

        $mid = $sessionInfo->mid;

        //ACK MY_MID SERIAL
        $message = "ACK " . $mid . " " . $request->get('serial_number') . "\n";

        $result = (new TcpConnection($mec_ip,$mec_port))->tcpSend($message);


        $resArr = explode("\n", $result);
        
        $resCode = explode(" ",$resArr[0]);

        if($resCode[0] == "11003")  return $this->respondWithAckHaveMessage();

        return response()->json(['code' => 1, 'msg' => '请求成功']);

    }




}