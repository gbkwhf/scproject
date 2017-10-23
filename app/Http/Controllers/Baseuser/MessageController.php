<?php

namespace App\Http\Controllers\Baseuser;
use App\MessageModel;
use Acme\Exceptions\ValidationErrorException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
class MessageController extends Controller
{
    //站内消息
    public function messageList(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'page'=>'string',
            'home'=>'string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $start=$request->page<=1?0:(($request->page)-1)*10;//分页
        if ($request->home){
            $res_message = MessageModel::where('user_id',$user_id)->orderBy('created_at','desc')->get();
            $res['list'] = MessageModel::where('user_id',$user_id)->orderBy('created_at','desc')->skip($start)->take(10)->get();
            $res['count'] = count($res_message);
        }else{
            $res = MessageModel::where('user_id',$user_id)->orderBy('created_at','desc')->skip($start)->take(10)->get();
        }

        return $this->respond($this->format($res));

    }
}
