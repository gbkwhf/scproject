<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1
 * Time: 9:58
 */

namespace App\Http\Controllers\HandleProfession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


/**
 * Class CreateOrdersController
 * @package App\Http\Controllers\HandleProfession
 * 该控制器主要作用是完成创建订单模块功能
 */
class CreateOrdersController extends Controller{


    //1.创建订单（常用：会员自己主动购买）
    public function createOrders(Request $request)
    {

        //1表示使用新地址     2表示使用已有的地址（如果传地址id则使用该地址，否则使用该用户的默认地址，如果没有默认地址则报错，提示地址没填写）
        if(empty($request->flag)) return $this->setStatusCode(9999)->respondWithError($this->message);

        if($request->flag == 1){ //使用新地址

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

        }elseif($request->flag == 2){ //使用已有地址

            $validator = $this->setRules([
                'ss' => 'required|string',
                'name' => 'required|string', //收货人姓名
                'mobile' =>[ //收货人电话： 既能验证座机号码又能验证手机号码的正则
                    'required',
                    'regex:/^1[34578][0-9]{9}$|(^0\\d{2}-?\\d{8}$)|(^0\\d{3}-?\\d{7}$)|(^\\(0\\d{2}\\)-?\\d{8}$)|(^\\(0\\d{3}\\)-?\\d{7}$)$/'
                ],
                'address' => 'required|string', //收货详细地址
            ])
                ->_validate($request->all());
            if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        }else{ //如果$flag的值不为1，或者2那么就是无效的值
            return $this->setStatusCode(9999)->respondWithError($this->message);
        }

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        //参数校验通过后，去查看购物车中选中的商品都有哪些
        $goods_info =  \DB::table('ys_goods_car')->where('user_id',$user_id)->where('state',1)->get();
        if(empty($goods_info)){ //没有找到商品信息 1043
            return $this->setStatusCode(1043)->respondWithError($this->message);
        }


        $supplier_id_class = [];
        $require_amount = 0; //购物车商品总金额
        /**
         * 计算返利份数 和 购物车商品总金额
         */
            //注意：返利是给用户返利的，而不是给供应商和经销商。并且返利是按份数返利的，也就是满足设定的金额就返利1份
            $returns = 0; //该笔购物车订单总的支持返利的金额
            foreach($goods_info as $k=>$v){
                if($v->first_class_id != 4){ //只有一级分类id为4的商品不支持返利，其他的都支持返利,这里计算出总的可以支持返利的金额
                    $returns += ($v->number) * ($v->goods_price);
                }
                array_push($supplier_id_class,$v->supplier_id);

                $require_amount += ($v->number) * ($v->goods_price);
            }
            $return_rule = getenv('RETURN_RULE');
            //计算返利的份数
            $copies = floor($returns / $return_rule);


         /**
          * 按经销商分别计算其购买其产品的总金额
          */
             $supplier_id_arr = array_values(array_unique($supplier_id_class));
             $tmp_arr = array_fill(0,count($supplier_id_arr),[]);
             foreach($goods_info as $k=>$v){
                 foreach($supplier_id_arr as $kk=>$vv){
                         if($v->supplier_id == $vv){
                             array_push($tmp_arr[$kk],$v);
                         }
                 }
             }
             //数组整理完毕 $tmp_arr 就是根据不同经销商分类得到的


         /**
          * 按照主订单和子订单需要的格式分别组装数据如下
          */
             //1.主订单 （主订单号  用户id  收货地址  收货人姓名  收货人手机号码  需要的总金额 支持返现的份数）
              $base_order_id =  generatorOrderIdNew();

              $base = [
                    'id' => $base_order_id, //主订单id
                    'user_id' => $user_id,
                    'create_time' => date("Y-m-d H:i:s"),
                    'state' => 0,//订单状态0未付款，1，已付款
                    'receive_address'=>$request->address,//收货地址
                    'require_amount'=>$require_amount,//需要的总金额
                    'receive_mobile'=>$request->mobile, //收货人手机号码
                    'receive_name'=>$request->name,//收货人姓名
                    'rebate_num'=>$copies //支持返现的份数
              ];

             //2.子订单 （主订单号 子订单号  子订单总金额 供应商id）
             //3.子订单和商品的对应表 （子订单id  商品id  购买数量）
              $sub_arr = []; //子订单
              $sub_goods = []; //子订单和商品对应数组
              foreach($tmp_arr as $k=>$v){

                  $sub_order_id = generatorOrderIdNew(); //子订单id
                  $price = 0;
                  if(is_array($v) && !empty($v)){

                      foreach($v as $kk=>$vv){
                          $price += ($vv->number) * ($vv->goods_price);
                          $sub_good_tmp = [
                               'sub_id' =>$sub_order_id,
                               'goods_id'=>$vv->goods_id,//商品id
                               'num'=>$vv->number //商品数量
                          ];
                          array_push($sub_goods,$sub_good_tmp);
                      }

                      $sub = [
                          'id' =>$sub_order_id, //子订单id
                          'base_id'=>$base_order_id, //主订单id
                          'supplier_id'=>$v[0]->supplier_id,//供应商id
                          'price'=>$price,//购买该供应商所有商品的总价格
                      ];
                      array_push($sub_arr,$sub);

                  }else{ //其实这一步错误是因为数据错误导致的
                      return $this->setStatusCode(9998)->respondWithError($this->message);
                  }
              }



        \DB::beginTransaction(); //开启事务
        //生成订单分为如下三步：
        //(1)生成主订单（包含地址信息和总金额信息）
         $insert1 = \DB::table('ys_base_order')->insert($base);

        //(2)生成子订单（每个经销商一条记录，记录条数等于经销商个数
         $insert2 =  \DB::table('ys_sub_order')->insert($sub_arr);

        //(3)生成子订单和商品的对应表信息
         $insert3 = \DB::table('ys_order_goods')->insert($sub_goods);


        if($request->flag == 1) { //使用新地址
            //判断该用户是否有地址记录，如果有则直接插入该地址即可，如果没有则插入该地址，并且设置为默认地址
            $is_exist = \DB::table('ys_user_addresses')->where('user_id',$user_id)->first();
            $is_default = empty($is_exist) ? 1 : 0;
            $insert4 = \DB::table('ys_user_addresses')->insert([

                            'user_id' => $user_id,
                            'name' => addslashes($request->name),
                            'mobile' => $request->mobile,
                            'area_id' => empty($request->area_id) ? "" : $request->area_id,
                            'address' => $request->address,
                            'created_at' => \DB::Raw('now()'),
                            'updated_at' => \DB::Raw('now()'),
                            'is_default' => $is_default,
                        ]);
        }else{
            $insert4 = 1;
        }

        /**
         * 最后提交事务，并且返回主订单id
         */
        if ($insert1 && $insert2 && $insert3 && $insert4) {
            \DB::commit();
            return  $this->respond($this->format(['order_id'=>$base_order_id]));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }


    //员工给会员创建订单  ---->走线下支付(登陆者身份是员工)
    public function employeeGivCreateOrders(Request $request){


        //1表示使用新地址     2表示使用已有的地址（如果传地址id则使用该地址，否则使用该用户的默认地址，如果没有默认地址则报错，提示地址没填写）
        if(empty($request->flag)) return $this->setStatusCode(9999)->respondWithError($this->message);

        if($request->flag == 1){ //使用新地址

            $validator = $this->setRules([
                'ss' => 'required|string',
                'phone' => 'required|regex:/^1[34578][0-9]{9}$/', //会员的电话号码
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

        }elseif($request->flag == 2){ //使用已有地址

            $validator = $this->setRules([
                'ss' => 'required|string',
                'phone' => 'required|regex:/^1[34578][0-9]{9}$/', //会员的电话号码
                'name' => 'required|string', //收货人姓名
                'mobile' =>[ //收货人电话： 既能验证座机号码又能验证手机号码的正则
                    'required',
                    'regex:/^1[34578][0-9]{9}$|(^0\\d{2}-?\\d{8}$)|(^0\\d{3}-?\\d{7}$)|(^\\(0\\d{2}\\)-?\\d{8}$)|(^\\(0\\d{3}\\)-?\\d{7}$)$/'
                ],
                'address' => 'required|string', //收货详细地址
            ])
                ->_validate($request->all());
            if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        }else{ //如果$flag的值不为1，或者2那么就是无效的值
            return $this->setStatusCode(9999)->respondWithError($this->message);
        }

        $user_id = $this->getUserIdBySession($request->ss); //获取员工id

        //首先查看会员手机号码是否存在，如果不存在则让其注册后再买
         $is_member = \DB::table('ys_member')->where('mobile',$request->phone)->first();
         if(empty($is_member)){ //该用户还不是会员
             return $this->setStatusCode(1044)->respondWithError($this->message);
         }
        //接着检查该购买者身份是否是员工
         $is_employee = \DB::table('ys_employee')->where('user_id',$user_id)->first();
        if(empty($is_employee)){ //您不是员工
            return $this->setStatusCode(1045)->respondWithError($this->message);
        }


        //参数校验通过后，去查看购物车中选中的商品都有哪些
        $goods_info =  \DB::table('ys_goods_car')->where('user_id',$user_id)->where('state',1)->get();
        if(empty($goods_info)){ //没有找到商品信息 1043
            return $this->setStatusCode(1043)->respondWithError($this->message);
        }

        //需要清空购物车中的商品id
        $delete_id_arr = [];
        $supplier_id_class = [];
        $require_amount = 0; //购物车商品总金额
        /**
         * 计算返利份数 和 购物车商品总金额
         */
        //注意：返利是给用户返利的，而不是给供应商和经销商。并且返利是按份数返利的，也就是满足设定的金额就返利1份
        $returns = 0; //该笔购物车订单总的支持返利的金额
        foreach($goods_info as $k=>$v){
            if($v->first_class_id != 4){ //只有一级分类id为4的商品不支持返利，其他的都支持返利,这里计算出总的可以支持返利的金额
                $returns += ($v->number) * ($v->goods_price);
            }
            array_push($supplier_id_class,$v->supplier_id);

            array_push($delete_id_arr,$v->id);

            $require_amount += ($v->number) * ($v->goods_price);
        }
        $return_rule = getenv('RETURN_RULE');
        //计算返利的份数
        $copies = floor($returns / $return_rule);


        /**
         * 按经销商分别计算其购买其产品的总金额
         */
        $supplier_id_arr = array_values(array_unique($supplier_id_class));
        $tmp_arr = array_fill(0,count($supplier_id_arr),[]);
        foreach($goods_info as $k=>$v){
            foreach($supplier_id_arr as $kk=>$vv){
                if($v->supplier_id == $vv){
                    array_push($tmp_arr[$kk],$v);
                }
            }
        }
        //数组整理完毕 $tmp_arr 就是根据不同经销商分类得到的


        /**
         * 按照主订单和子订单需要的格式分别组装数据如下
         */
        //1.主订单 （主订单号  用户id  收货地址  收货人姓名  收货人手机号码  需要的总金额 支持返现的份数）
        $base_order_id =  generatorOrderIdNew();

        $base = [
            'id' => $base_order_id, //主订单id
            'user_id' => $is_member->user_id,
            'create_time' => date("Y-m-d H:i:s"),
            'pay_time'=>date("Y-m-d H:i:s"),
            'pay_type'=>2,//付款方式:1微信，2线下支付
            'employee_id'=>$user_id,//员工id也就是本次替用户购买者的id
            'state' => 1,//订单状态0未付款，1，已付款
            'receive_address'=>$request->address,//收货地址
            'amount'=>$require_amount,//实际收到的总金额
            'receive_mobile'=>$request->mobile, //收货人手机号码
            'receive_name'=>$request->name,//收货人姓名
            'require_amount'=>$require_amount,//需要的总金额
            'rebate_num'=>$copies //支持返现的份数
        ];

        //2.子订单 （主订单号 子订单号  子订单总金额 供应商id）
        //3.子订单和商品的对应表 （子订单id  商品id  购买数量）
        $sub_arr = []; //子订单
        $sub_goods = []; //子订单和商品对应数组
        foreach($tmp_arr as $k=>$v){

            $sub_order_id = generatorOrderIdNew(); //子订单id
            $price = 0;
            if(is_array($v) && !empty($v)){

                foreach($v as $kk=>$vv){
                    $price += ($vv->number) * ($vv->goods_price);
                    $sub_good_tmp = [
                        'sub_id' =>$sub_order_id,
                        'goods_id'=>$vv->goods_id,//商品id
                        'num'=>$vv->number //商品数量
                    ];
                    array_push($sub_goods,$sub_good_tmp);
                }

                $sub = [
                    'id' =>$sub_order_id, //子订单id
                    'base_id'=>$base_order_id, //主订单id
                    'supplier_id'=>$v[0]->supplier_id,//供应商id
                    'price'=>$price,//购买该供应商所有商品的总价格
                ];
                array_push($sub_arr,$sub);

            }else{ //其实这一步错误是因为数据错误导致的
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }
        }



        \DB::beginTransaction(); //开启事务
        //生成订单分为如下三步：
        //(1)生成主订单（包含地址信息和总金额信息）
        $insert1 = \DB::table('ys_base_order')->insert($base);

        //(2)生成子订单（每个经销商一条记录，记录条数等于经销商个数
        $insert2 =  \DB::table('ys_sub_order')->insert($sub_arr);

        //(3)生成子订单和商品的对应表信息
        $insert3 = \DB::table('ys_order_goods')->insert($sub_goods);

        //(4)如果使用新地址，则把新地址收入到地址库中
        if($request->flag == 1) { //使用新地址
            //判断该用户是否有地址记录，如果有则直接插入该地址即可，如果没有则插入该地址，并且设置为默认地址
            $is_exist = \DB::table('ys_user_addresses')->where('user_id',$user_id)->first();
            $is_default = empty($is_exist) ? 1 : 0;
            $insert4 = \DB::table('ys_user_addresses')->insert([

                'user_id' => $user_id,
                'name' => addslashes($request->name),
                'mobile' => $request->mobile,
                'area_id' => empty($request->area_id) ? "" : $request->area_id,
                'address' => $request->address,
                'created_at' => \DB::Raw('now()'),
                'updated_at' => \DB::Raw('now()'),
                'is_default' => $is_default,
            ]);
        }else{
            $insert4 = 1;
        }
        //(5)如果付款成功，则把购物车中已购买的商品清空 $delete_id_arr
        $delete = \DB::table('ys_goods_car')->where('user_id',$user_id)->whereIn('id',$delete_id_arr)->delete();
        /**
         * 最后提交事务，并且返回主订单id
         */
        if ($insert1 && $insert2 && $insert3 && $insert4 && $delete) {
            \DB::commit();
            return  $this->respond($this->format(['order_id'=>$base_order_id]));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }









}