<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 15:03
 */

namespace App\Http\Controllers\HandleProfession;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class AddressManageController
 * @package App\Http\Controllers\HandleProfession
 * 该控制器主要用于地址管理功能的
 */
class AddressManageController  extends  Controller{



    //1.添加收货地址
    public function addGoodsAddress(Request $request)
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









    }






}