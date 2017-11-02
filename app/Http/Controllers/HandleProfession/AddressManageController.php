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
            'name' => 'required|string', //收货人姓名
            'mobile' =>[ //收货人电话： 既能验证座机号码又能验证手机号码的正则
                           'required',
                           'regex:/^1[34578][0-9]{9}$|(^0\\d{2}-?\\d{8}$)|(^0\\d{3}-?\\d{7}$)|(^\\(0\\d{2}\\)-?\\d{8}$)|(^\\(0\\d{3}\\)-?\\d{7}$)$/'
                      ],
            'area_id' => 'string|between:6,6', //省市区id
            'address' => 'required|string', //收货详细地址
        ])
            ->_validate($request->all());

        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        //判断该用户是否有地址记录，如果有则直接插入该地址即可，如果没有则插入该地址，并且设置为默认地址
        $is_exist = \DB::table('ys_user_addresses')->where('user_id',$user_id)->first();
        $is_default = empty($is_exist) ? 1 : 0;
        $insert = \DB::table('ys_user_addresses')->insertGetId([
             'user_id' => $user_id,
             'name' => addslashes($request->name),
             'mobile' => $request->mobile,
             'area_id' => empty($request->area_id) ? "" : $request->area_id,
             'address' => $request->address,
             'created_at' => \DB::Raw('now()'),
             'updated_at' => \DB::Raw('now()'),
             'is_default' => $is_default,
        ]);
        if($insert)
             return  $this->respond($this->format(['address_id'=>$insert],true));
        else
            return $this->setStatusCode(9998)->respondWithError($this->message);

    }

    //2.获取地址（分为获取所有地址和单条地址（单条主要用于修改用））
    public function getGoodsAddressInfo(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'address_id' => 'integer', //地址id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //如果传递地址id，则获取单条地址信息，否则返回该用户的全部地址
        $address = \DB::table('ys_user_addresses')
                   ->select('id as address_id','name','mobile','area_id','address','is_default','updated_at as date')
                   ->where('user_id',$user_id);

        if(empty($request->address_id)){
            $address = $address->orderBy('updated_at','desc')->get();
        }else{
            $address = $address->where('id',$request->address_id)->get();
        }

        $data =  empty($address) ? [] : $address;
        return  $this->respond($this->format($data));
    }


    //3.编辑收货地址（主要处理编辑的表单数据）
    public function editGoodsAddress(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'address_id' => 'required|integer', //地址id
            'name' => 'string', //收货人姓名
            'mobile' =>[ //收货人电话： 既能验证座机号码又能验证手机号码的正则
                'regex:/^1[34578][0-9]{9}$|(^0\\d{2}-?\\d{8}$)|(^0\\d{3}-?\\d{7}$)|(^\\(0\\d{2}\\)-?\\d{8}$)|(^\\(0\\d{3}\\)-?\\d{7}$)$/'
            ],
            'area_id' => 'string|between:6,6', //省市区id
            'address' => 'string', //收货详细地址
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $tmp = $request->all();
        foreach($tmp as $k=>$v){
            if(($k == 'ss') || ($k == 'address_id')){
                unset($tmp[$k]);
            }elseif(empty($v)){
                unset($tmp[$k]);
            }
        }

        $info = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->first();
        if(empty($info)){ //该收货地址不存在
            return $this->setStatusCode(1041)->respondWithError($this->message);
        }

        $name = isset($tmp['name']) ? $tmp['name'] : $info->name;
        $insert = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->update([
            'name' => addslashes($name),
            'mobile' => isset($tmp['mobile']) ? $tmp['mobile'] : $info->mobile,
            'area_id' => isset($tmp['area_id']) ? $tmp['area_id'] : $info->area_id,
            'address' => isset($tmp['address']) ? $tmp['address'] : $info->address,
            'updated_at' => \DB::Raw('now()'),
        ]);
        if($insert)
            return  $this->respond($this->format([],true));
        else
            return $this->setStatusCode(9998)->respondWithError($this->message);
    }

    //4.设置默认地址
    public function handleDefaultAddress(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'address_id' => 'required|integer', //地址id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $info = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->first();
        if(empty($info)){ //该收货地址不存在
            return $this->setStatusCode(1041)->respondWithError($this->message);
        }

        //默认收货地址每个用户只能有一个，因此必须先要把其他地址变成普通地址，然后把该传递上来的地址设置为默认地址
        $id_arr =  \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('is_default',1)->lists('id');

        \DB::beginTransaction(); //开启事务

        $update1 = \DB::table('ys_user_addresses')->where('user_id',$user_id)->whereIn('id',$id_arr)->update([
            'is_default' => 0,
            'updated_at' => \DB::Raw('now()')
        ]);

        $update2 = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->update([
            'is_default' => 1,
            'updated_at' => \DB::Raw('now()')
        ]);

        if ($update1 && $update2) {
            \DB::commit();
            return  $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }

    //5.删除收货地址
    public function deleteAddress(Request $request){

        $validator = $this->setRules([
            'ss' => 'required|string',
            'address_id' => 'required|integer', //地址id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $info = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->first();
        if(empty($info)){ //该收货地址不存在
            return $this->setStatusCode(1041)->respondWithError($this->message);
        }

        \DB::beginTransaction(); //开启事务
        $delete = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$request->address_id)->delete();

        //如果默认地址删除了，那么自动把最早设置的地址设为默认地址即可
        if($info->is_default == 1){ //表示删除的就是默认地址

            $address = \DB::table('ys_user_addresses')->where('user_id',$user_id)->orderBy('created_at','asc')->first();
            if(!empty($address)){
                $update = \DB::table('ys_user_addresses')->where('user_id',$user_id)->where('id',$address->id)->update([
                    'is_default' => 1,
                    'updated_at' => \DB::Raw('now()')
                ]);
            }else{ //如果删除默认地址后，该用户地址库中没地址了，那么也不做任何处理
                $update = 1;
            }
        }else{ //如果删除的不是默认地址那么就不做处理
            $update = 1;
        }

        if ($delete && $update){
            \DB::commit();
            return  $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }



}