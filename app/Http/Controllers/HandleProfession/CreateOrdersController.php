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

        $update_goods_arr = []; //购买商品的goods_id数组
        $return_all_profit = 0; //计算该笔订单返利区的商品总利润
        $delete_id_arr = [];  //需要清空购物车中的商品id
        $supplier_id_class = [];//供销商id数组
        $require_amount = 0; //购物车商品总金额
        /**
         * 计算返利份数 和 购物车商品总金额
         */
            //注意：返利是给用户返利的，而不是给供应商和经销商。并且返利是按份数返利的，也就是满足设定的金额就返利1份
            $returns = 0; //该笔购物车订单总的支持返利的金额
            foreach($goods_info as $k=>$v){
                if($v->first_class_id != 4){ //只有一级分类id为4的商品不支持返利，其他的都支持返利,这里计算出总的可以支持返利的金额
                    $returns += ($v->number) * ($v->goods_price);
                    $return_all_profit += ($v->number) * ($v->goods_price - $v->cost_price);
                }
                array_push($supplier_id_class,$v->supplier_id);

                array_push($delete_id_arr,$v->id);

                array_push($update_goods_arr,$v->goods_id);

                $require_amount += ($v->number) * ($v->goods_price);
            }
            $return_rule = getenv('RETURN_RULE');
            //计算返利的份数
            $copies = floor($returns / $return_rule);


        /**
         *  判断商品库存是否够
         */
        $complete_arr = []; //用于更新销量和库存
        //查询一下购买商品的详情,进而判断商品库存是否充足
        $tmp_goods_info = \DB::table('ys_goods')->whereIn('id',$update_goods_arr)->select('id as goods_id','num','sales')->get();
        foreach($goods_info as $k=>$v){
            foreach($tmp_goods_info as $kk=>$vv){
                if($v->goods_id == $vv->goods_id){
                    //进一步比较两个数据的大小，判断库存是否充足
                    if(($vv->num - $v->number) < 0){ //库存不足
                        return $this->setStatusCode(1046)->respondWithError($this->message);
                    }

                    $make_tmp_arr = [
                        'goods_id'=>$vv->goods_id, //商品id
                        'buy_num'=>$v->number, //购买数量
                        'rest_num'=>$vv->num, //库存剩余数量
                        'sales'=>$vv->sales //该商品销量
                    ];
                    array_push($complete_arr,$make_tmp_arr);
                }
            }
        }


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
                    'rebate_num'=>$copies, //支持返现的份数
                    'all_profit'=>$return_all_profit, //该笔订单的所有商品总利润
                    'user_remark'=>$request->user_remark
                    
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

        //(4)如果使用新地址，则把新地址写入到地址库中去
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


    //2.员工给会员创建订单  ---->走线下支付(登陆者身份是员工)
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



        $update_goods_arr = []; //购买商品的goods_id数组
        $return_all_profit = 0; //计算该笔订单返利区的商品总利润
        $delete_id_arr = [];  //需要清空购物车中的商品id
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
                $return_all_profit += ($v->number) * ($v->goods_price - $v->cost_price);
            }
            array_push($supplier_id_class,$v->supplier_id);

            array_push($delete_id_arr,$v->id);

            array_push($update_goods_arr,$v->goods_id);

            $require_amount += ($v->number) * ($v->goods_price);
        }
        $return_rule = getenv('RETURN_RULE');
        //计算返利的份数
        $copies = floor($returns / $return_rule);


        /**
         *  判断商品库存是否够
         */
            $complete_arr = []; //用于更新销量和库存
            //查询一下购买商品的详情,进而判断商品库存是否充足
            $tmp_goods_info = \DB::table('ys_goods')->whereIn('id',$update_goods_arr)->select('id as goods_id','num','sales')->get();
            foreach($goods_info as $k=>$v){
                foreach($tmp_goods_info as $kk=>$vv){
                     if($v->goods_id == $vv->goods_id){
                           //进一步比较两个数据的大小，判断库存是否充足
                         if(($vv->num - $v->number) < 0){ //库存不足
                             return $this->setStatusCode(1046)->respondWithError($this->message);
                         }

                         $make_tmp_arr = [
                             'goods_id'=>$vv->goods_id, //商品id
                             'buy_num'=>$v->number, //购买数量
                             'rest_num'=>$vv->num, //库存剩余数量
                             'sales'=>$vv->sales //该商品销量
                         ];
                         array_push($complete_arr,$make_tmp_arr);
                     }
                }
            }


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
            'rebate_num'=>$copies, //支持返现的份数
            'all_profit'=>$return_all_profit, //该笔订单的所有商品总利润
            'user_remark'=>$request->user_remark            
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

        //(6)购买成功后更新商品的销量和库存
        $update_arr = [];
        foreach($complete_arr as $k=>$v){

            $update = \DB::table('ys_goods')->where('id',$v['goods_id'])->update([
                            'num'=>$v['rest_num'] - $v['buy_num'],
                            'sales'=>$v['sales'] + $v['buy_num'],
                            'updated_at'=>\DB::Raw('Now()')
                        ]);

            array_push($update_arr,$update);
        }

        //下面这个循环的目的是为了保证使用循环更新时的每一项都成功
        foreach($update_arr as $k=>$v){
            if(!$v){
                \DB::rollBack();
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }
        }

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


    //3.员工确认完成用户自己创建的订单（线下收钱，使得订单结束，变成已支付）
    public function employeeAckCompleteOrder(Request $request){

        $validator = $this->setRules([
            'ss' => 'required|string', //员工session
            'base_order_id' => 'required|string' //主订单id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //（1）首先判定角色身份，必须是员工才可以调用该接口
        $is_employee = \DB::table('ys_employee')->where('user_id',$user_id)->first();
        if(empty($is_employee)){ //表示不是员工
            return $this->setStatusCode(1045)->respondWithError($this->message);
        }
        //(2)如果是员工，那么就要把订单状态和商品销量、库存等都需要修改
        $order_info = \DB::table('ys_base_order as a')
                          ->leftjoin('ys_sub_order as b','a.id','=','b.base_id')
                          ->leftjoin('ys_order_goods as c','b.id','=','c.sub_id')
                          ->leftjoin('ys_goods as d','c.goods_id','=','d.id')
                          ->select('a.require_amount','c.goods_id','c.num as buy_num','d.num as rest_num','d.sales')
                          ->where('a.id',$request->base_order_id)
                          ->get();


       if(empty($order_info)){ //订单不存在
           return $this->setStatusCode(6100)->respondWithError($this->message);
       }
        $base = [
            'pay_time'=>date("Y-m-d H:i:s"),
            'pay_type'=>2,//付款方式:1微信，2线下支付
            'employee_id'=>$user_id,//员工id也就是本次替用户购买者的id
            'state' => 1,//订单状态0未付款，1，已付款
            'amount'=>$order_info[0]->require_amount,//实际收到的总金额
        ];

        \DB::beginTransaction(); //开启事务
        //更新主订单信息
        $update1 = \DB::table('ys_base_order')->where('id',$request->base_order_id)->update($base);

        //更新商品销量和库存
        $update_arr = [];
        foreach($order_info as $k=>$v){

            $update = \DB::table('ys_goods')->where('id',$v->goods_id)->update([
                'num'=>$v->rest_num - $v->buy_num,
                'sales'=>$v->sales + $v->buy_num,
                'updated_at'=>\DB::Raw('Now()')
            ]);
            array_push($update_arr,$update);
        }

        //下面这个循环的目的是为了保证使用循环更新时的每一项都成功
        foreach($update_arr as $k=>$v){
            if(!$v){
                \DB::rollBack();
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }
        }
        /**
         * 最后提交事务，并且返回主订单id
         */
        if ($update1) {
            \DB::commit();
            return  $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }


     //4.获取订单列表（已完成的）
    public function getOrderLists(Request $request){


        $validator = $this->setRules([
            'ss' => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

         //获取自己购买的所有商品列表
         $order_info = \DB::table('ys_base_order as a') //主订单
                       ->leftjoin('ys_sub_order as b','a.id','=','b.base_id') //子订单
                       ->leftjoin('ys_order_goods as c','b.id','=','c.sub_id') //子订单和商品对应表
                       ->leftjoin('ys_goods as d','d.id','=','c.goods_id')
                       ->select('a.create_time','b.id as sub_order_id','b.price','b.express_name','b.express_num','c.goods_id','c.num','d.name as goods_name','d.name as image')
                       ->where('a.state',1) //0未付款  1已付款
                       ->where('user_id',$user_id)
                       ->orderBy('create_time','desc')
                       ->get();

         $goods_id_arr = []; //购买过的所有商品id
         $order_arr_tmp = [];
         //按照子订单号进行分类

          if(!empty($order_info)){
              foreach($order_info as $k=>$v){

                  $order_info[$k]->image = "";
                  array_push($goods_id_arr,$v->goods_id);
                  array_push($order_arr_tmp,$v->sub_order_id);
              }

              //商品图片
              $goods_info = \DB::table('ys_goods_image')
                              ->select('goods_id','image')
                              ->whereIn('goods_id',$goods_id_arr)
                              ->groupBy('goods_id')
                              ->get();

              //调用该方法格式化图片，并且使得数据按照子订单号递归分组
              $res = $this->recursiveGroup($order_info,$order_arr_tmp,$goods_info);

              //使用下面这个循环使得通用的数据提出来，然后单独把不同信息方数组里面
              $data = array_fill(0,count($res),[]);
              foreach($res as $k=>$v){
                  $number = 0;
                  $tmp_ar = [];
                  if(is_array($v) && !empty($v)){
                      foreach($v as $key=>$val){
                         $number += $val->num;
                          array_push($tmp_ar,['goods_id'=>$val->goods_id,'goods_name'=>$val->goods_name,'number'=>$val->num,'image'=>$val->image]);
                      }
                  }
                  $data[$k] = [
                      'sub_order_id'=>$v[0]->sub_order_id,
                      'create_time'=>$v[0]->create_time,//下单时间
                      'price'=>$v[0]->price,
                      'express_name'=>is_null($v[0]->express_name) ? "" :$v[0]->express_name,
                      'express_num'=>is_null($v[0]->express_num) ? "" : $v[0]->express_num,
                      'number'=>$number,
                      'goods_info'=>$tmp_ar
                  ];
              }
              $result = $data;
          }else{
              $result = [];
          }

        return  $this->respond($this->format($result));
    }


    //二维对象数组进行按照某个值进行分类
    private function recursiveGroup($arr,$order_arr_tmp,$goods_info){

        $http = getenv('HTTP_REQUEST_URL');
        $order_arr = array_values(array_unique($order_arr_tmp));
        $tmp = array_fill(0,count($order_arr),[]);


        foreach($arr as $k=>$v){

            $arr[$k]->image = ""; //先把所有的image置空
            foreach($goods_info as $key=>$val){
                if($v->goods_id === $val->goods_id){
                    $arr[$k]->image = empty($val->image) ? "" : $http.$val->image;
                }
            }

            foreach($order_arr as $kk=>$vv){
                if($v->sub_order_id == $vv){
                    array_push($tmp[$kk],$v);
                }
            }
        }
        return $tmp;
    }



    //5.获取订单详情(根据子订单id获取订单详情---拆分后)
    public function getSubOrderInfo(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'sub_order_id' => 'required|string' //子订单id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //首先判断该子订单是否是该会员买的，如果不是则无权获取该订单详情
        $base_info = \DB::table('ys_sub_order as a')->leftjoin('ys_base_order as b','a.base_id','=','b.id')
                      ->select('a.id as sub_order_id','a.base_id','a.price','a.express_name','a.express_num',
                          'b.user_id','b.create_time','b.pay_type','b.employee_id','b.state','b.receive_address',
                          'b.require_amount','b.receive_mobile','b.receive_name','b.user_remark')
                      ->where('a.id',$request->sub_order_id)->first();

        if(empty($base_info)){ //提示：该订单不存在
            return $this->setStatusCode(6100)->respondWithError($this->message);
        }


        //获取自己购买的该子订单的商品列表
        $goods_info = \DB::table('ys_sub_order as b') //子订单
                            ->leftjoin('ys_order_goods as c','b.id','=','c.sub_id') //子订单和商品对应表
                            ->leftjoin('ys_goods as d','d.id','=','c.goods_id')
                            ->leftjoin('ys_goods_image as e','d.id','=','e.goods_id')
                            ->select('c.goods_id','c.num','d.name as goods_name','d.price as goods_price','e.image')
                            ->where('b.id',$request->sub_order_id)
                            ->groupBy('d.id')
                            ->get();

        $http = getenv('HTTP_REQUEST_URL');
        foreach($goods_info as $k=>$v){
            $goods_info[$k]->image = empty($v->image) ? "" : $http.$v->image;
        }
        $express=null;
        if($base_info->express_name !=''){
        	$express=\App\ExpressModel::where('id',$base_info->express_name)->first();
        	if(empty($express)){
        		$express=(object)array();
        		$express->name='';
        		$express->e_name='';
        	}
        }
        
  
        $data = [];
        $data['address'] = $base_info->receive_address; //收货地址
        $data['mobile']  = $base_info->receive_mobile; //收货人电话
        $data['name']    = $base_info->receive_name; //收货人
        $data['sub_order_id'] = $base_info->sub_order_id; //子订单id
        $data['base_order_id'] = $base_info->base_id; //主订单id
        $data['create_time'] = $base_info->create_time; //下单时间
        $data['price'] =$base_info->price; //子订单总价
        $data['pay_type'] = $base_info->pay_type; //付款方式：1微信，2线下支付
        $data['state'] = $base_info->state; //订单状态：0未付款，1，已付款
        $data['express_name'] =empty($base_info->express_num) ? "" : $express->name; //快递名称
        $data['express_num'] = is_null($base_info->express_num) ? "" : $base_info->express_num; //快递单号
        $data['goods_list'] = $goods_info; //子订单包含的商品列表
        $data['user_remark'] =$base_info->user_remark; //用户备注
        
        $express_arr=null;
        if($express){
        	$express_arr=json_decode(file_get_contents('http://www.kuaidi100.com/query?type='.$express->e_name.'&postid='.$base_info->express_num));
        	
        	//0：在途中,1：已发货，2：疑难件，3： 已签收 ，4：已退货
        	$state_arr=[0=>'在途中',1=>'已发货',2=>'疑难件',3=>'已签收',4=>'已退货'];
        	$express_info['state']=$state_arr[$express_arr->state];
        	$express_info['data']=$express_arr->data;
        }else{        	
        	if($base_info->express_name ==''){
        		$express_info['state']='未发货';
        		$express_info['data']=[];
        	}else{
        		$express_info['state']='未查询到快递信息';
        		$express_info['data']=[];        		
        	}
        }
        
        

        //物流信息
        $data['express']=$express_info;

        return  $this->respond($this->format($data));

    }

    //6.获取订单详情（根据主订单id获取详情----拆分前）
    public function getBaseOrderInfo(Request $request)
    {


        $validator = $this->setRules([
            'ss' => 'required|string',
            'base_order_id' => 'required|string' //主订单id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //首先判断该子订单是否是该会员买的，如果不是则无权获取该订单详情
        $base_info = \DB::table('ys_base_order as a')->leftjoin('ys_sub_order as b','a.id','=','b.base_id')
            ->select('b.id as sub_order_id','b.base_id','b.express_name','b.express_num',
                'a.user_id','a.create_time','a.pay_type','a.employee_id','a.state','a.receive_address',
                'a.require_amount','a.receive_mobile','a.receive_name','a.user_remark')
            ->where('a.id',$request->base_order_id)->get();

        if(empty($base_info)){ //提示：该订单不存在
            return $this->setStatusCode(6100)->respondWithError($this->message);
        }


        //获取自己购买的该子订单的商品列表
        $goods_info = \DB::table('ys_base_order as a') //主订单
                            ->leftjoin('ys_sub_order as b','a.id','=','b.base_id') //子订单
                            ->leftjoin('ys_order_goods as c','b.id','=','c.sub_id') //子订单和商品对应表
                            ->leftjoin('ys_goods as d','d.id','=','c.goods_id')
                            ->leftjoin('ys_goods_image as e','d.id','=','e.goods_id')
                            ->select('b.id as sub_order_id','c.goods_id','c.num','d.name as goods_name','d.price as goods_price','e.image')
                            ->where('a.id',$request->base_order_id)
                            ->groupBy('e.goods_id')
                            ->get();

        $http = getenv('HTTP_REQUEST_URL');
        foreach($goods_info as $k=>$v){
            $goods_info[$k]->image = empty($v->image) ? "" : $http.$v->image;
        }

        $tmp_info = [];
        foreach($base_info as $k=>$v) {
        	
        	$express=null;
        	if($v->express_name !=''){
        		$express=\App\ExpressModel::where('id',$v->express_name)->first();
        		if(empty($express)){
        			$express=(object)array();
        			$express->name='';
        			$express->e_name='';
        		}
        	}
        	$express_arr=null;
        	if($express){
        		$express_arr=json_decode(file_get_contents('http://www.kuaidi100.com/query?type='.$express->e_name.'&postid='.$v->express_num));
        		
        		//0：在途中,1：已发货，2：疑难件，3： 已签收 ，4：已退货
        		$state_arr=[0=>'在途中',1=>'已发货',2=>'疑难件',3=>'已签收',4=>'已退货'];
        		$express_info['state']=$state_arr[$express_arr->state];
        		$express_info['data']=$express_arr->data;
        	}else{        	
	        	if($v->express_name ==''){
	        		$express_info['state']='未发货';
	        		$express_info['data']=[];
	        	}else{
	        		$express_info['state']='未查询到快递信息';
	        		$express_info['data']=[];        		
	        	}
        }
        	
        	
            $make_arr = [
                'sub_order_id' => $v->sub_order_id, //子订单id
                'express_name' => empty($v->express_name) ? "" : $express->name, //快递名称
                'express_num' => is_null($v->express_num) ? "" : $v->express_num, //快递单号
                'goods_list' =>[], //子订单包含的商品列表
                'express'=>$express_info,
            ];
            array_push($tmp_info,$make_arr);
        }

       foreach($tmp_info as $k=>$v){
           foreach($goods_info as $kk=>$vv){
               if($v['sub_order_id'] == $vv->sub_order_id){
                   $arr_tmp = [
                       "goods_id"     => $vv->goods_id,
                       "num"          => $vv->num,
                       "goods_name"   => $vv->goods_name,
                       "goods_price"  => $vv->goods_price,
                       "image"        => $vv->image
                   ];
                   array_push($tmp_info[$k]['goods_list'],$arr_tmp);
               }
           }
       }

        $data = [];
        $data['base_order_id'] = $base_info[0]->base_id; //主订单id
        $data['address'] = $base_info[0]->receive_address; //收货地址
        $data['mobile']  = $base_info[0]->receive_mobile; //收货人电话
        $data['name']    = $base_info[0]->receive_name; //收货人
        $data['create_time'] = $base_info[0]->create_time; //下单时间
        $data['price'] =$base_info[0]->require_amount; //主订单总价
        $data['pay_type'] = $base_info[0]->pay_type; //付款方式：1微信，2线下支付
        $data['state'] = $base_info[0]->state; //订单状态：0未付款，1，已付款
        $data['info'] = $tmp_info;
        $data['user_remark'] =$base_info[0]->user_remark; //用户备注
        //用户信息
        $u_info=\App\MemberModel::where('user_id',$base_info[0]->user_id)->first();
        $data['user_name'] =$u_info->name; //用户名
        $data['user_mobile'] =$u_info->mobile; //用户名

        return  $this->respond($this->format($data));        
    }

    //员工给顾客下单记录
    public function getEmployeeOrder(Request $request)
    {
    
    
    	$validator = $this->setRules([
    			'ss' => 'required|string',
    			//'page' => '' 
    			])
    			->_validate($request->all());
    	if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
    
    	$user_id = $this->getUserIdBySession($request->ss); //获取用户id
    	$start = $request->page <= 1 ? 0 : (($request->page) - 1) * 10;//分页
    	
 		$orders=\App\BaseOrderModel::where('employee_id',$user_id)
 				->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
 				->orderBy('ys_base_order.pay_time','desc')
 				->select('ys_base_order.id','ys_base_order.pay_time','ys_base_order.amount','ys_member.name','ys_member.mobile')
 				->skip($start)->take(10)
 				->get();

 		
 		$http = getenv('HTTP_REQUEST_URL'); 		
 		foreach ($orders as &$val){
 			
 			$val['goods_total']=0; 			
 			$val['goods_list']=\App\SubOrderModel::where('base_id',$val->id)
	 			->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
	 			->leftjoin('ys_goods','ys_goods.id','=','ys_order_goods.goods_id')
	 			->leftjoin('ys_goods_image','ys_goods_image.goods_id','=','ys_goods.id')
	 			->selectRaw("ys_order_goods.num,ys_goods.name,concat('$http',ys_goods_image.image) as image")
	 			->groupBy('ys_order_goods.goods_id')
	 			->orderBy('ys_goods_image.id','asc')
	 			->get()->toArray();
 			foreach ($val['goods_list'] as $v){ 				
 				$val['goods_total']+=$v['num'];
 			}
 			
 		}

    	return  $this->respond($this->format($orders));
    }

}