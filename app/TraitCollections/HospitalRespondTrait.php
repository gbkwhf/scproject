<?php

namespace App\TraitCollections;

trait HospitalRespondTrait
{
    public function response($data = [])
    {
        return response()->json(['code' => 1,'msg' => '请求成功','result' => $data]);
    }

    public function respondNotExist($name='')
    {
        return response()->json(['code' => 9999,'msg' => '请求'.$name.'数据不存在']);
    }

    public function respondCreatedSuccess()
    {
        return response()->json(['code' => 1,'msg' => '数据创建成功']);
    }

    public function respondDeleteSuccess()
    {
        return response()->json(['code' => 1,'msg' => '数据删除成功']);
    }

    public function respondWithSysError()
    {
        return response()->json(['code' => 9998, 'msg' => '系统错误']);
    }
    
    public function respondWithParameterError()
    {
        return response()->json(['code' => 9999,'msg' => '参数错误']);
    }

    public function respondWithFileSizeError()
    {
        return response()->json(['code' => 2001, 'msg' => '文件大小超过限制']);
    }

    public function respondWithSendMessageSuccess()
    {
        return response()->json(['code' => 1, 'msg' => '消息发送成功']);
    }

    public function respondWithGetMessageError()
    {
        return response()->json(['code' => 2003, 'msg' => '获取消息失败']);
    }

    public function respondWithAckHaveMessage()
    {
        return response()->json(['code' => 2005, 'msg' => 'ACK响应队列中还有消息，需要用户再取一次']);
    }

    public function respondWithFileNotExist()
    {
        return response()->json(['code' => 2006, 'msg' => '文件不存在']);
    }

    public function respondWithSendMessageError()
    {
        return response()->json(['code' => 2002, 'msg' => '消息发送失败']);
    }

    public function respondWithInvalidSession()
    {
        return response()->json(['code' => 1011,'msg' => '无效的session']);
    }


}