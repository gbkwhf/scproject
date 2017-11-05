<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 14:44
 */

namespace App\Http\Controllers\HandleProfession;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


/**
 * Class CreateGoodsCarController
 * @package App\Http\Controllers\HandleProfession
 * 该控制器主要用于完成购物车功能
 */
class CreateGoodsCarController extends Controller{


    //1.创建购物车，给购物车中添加商品信息
    public function addGoodsCar(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'goods_id' => 'required|integer', //商品id
            'number' => 'required|integer|min:1', //商品数量，最少为1个
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //首先判断该商品是否存在
        $goods_info = \DB::table('ys_goods as a')->leftjoin('ys_goods_image as b','a.id','=','b.goods_id')
                            ->leftjoin('ys_goods_class as c','a.class_id','=','c.id')
                            ->select('a.id as goods_id','a.cost_price','a.supplier_id','a.name as goods_name','a.num','a.price','a.sales','b.image','c.first_id')
                            ->where('a.id',$request->goods_id)
                            ->where('a.state',1) //0下架1上架
                            ->groupBy('a.id')
                            ->first();

        if(empty($goods_info)){ //该商品不存在
            return $this->setStatusCode(1042)->respondWithError($this->message);
        }

        //判断该商品是否已经加入购物车了
        $is_exist = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('goods_id',$request->goods_id)->first();
        \DB::beginTransaction(); //开启事务
        if(empty($is_exist)){ //如果为空，则继续插入数据，否则不做任何处理

            $http = getenv('HTTP_REQUEST_URL');
            //改变图片链接，使其可以直接访问
            $goods_info->image =  empty($goods_info->image) ? "" : $http. $goods_info->image;

            //然后把收集到的数据插入数据库:购物车表
            $insert = \DB::table('ys_goods_car')->insert([

                'user_id' => $user_id,
                'goods_id' => $request->goods_id,
                'goods_name' => $goods_info->goods_name,
                'goods_price' => $goods_info->price, //商品定价
                'cost_price' => $goods_info->cost_price,//成本价
                'goods_url' => $goods_info->image,
                'number' => $request->number,
                'supplier_id' => $goods_info->supplier_id,//供应商id
                'first_class_id' => $goods_info->first_id,
                'state'=>1,//1选中  0不选中
                'created_at' => \DB::Raw('Now()'),
                'updated_at' => \DB::Raw('Now()'),
            ]);

        }else{ //如果该商品已经存在购物车，则直接返回成功状态即可

            $insert = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('goods_id',$request->goods_id)->update([
                'number'=>$is_exist->number + $request->number,
                'updated_at'=>\DB::Raw('Now()')
            ]);

        }

        if ($insert) {
            \DB::commit();
            return  $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }


    }

    //2.更改购物车中某条商品的数量（plus:加号   minus:减号 ） ---》该加减只能用于购物车中的加减
    public function updateGoodsNumber(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'goods_id' => 'required|integer', //商品id
            'symbol' => 'required|integer|in:1,2', //1:加号   2:减号
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);


        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //首先判断该商品是否存在
        $goods_info = \DB::table('ys_goods as a')->leftjoin('ys_goods_image as b','a.id','=','b.goods_id')
            ->leftjoin('ys_goods_class as c','a.class_id','=','c.id')
            ->select('a.id as goods_id','a.cost_price','a.supplier_id','a.name as goods_name','a.num','a.price','a.sales','b.image','c.first_id')
            ->where('a.id',$request->goods_id)
            ->where('a.state',1) //0下架1上架
            ->groupBy('a.id')
            ->first();

        if(empty($goods_info)){ //该商品不存在
            return $this->setStatusCode(1042)->respondWithError($this->message);
        }


        //接着判断该商品是否在购物车中
        $is_exist = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('goods_id',$request->goods_id)->first();
        \DB::beginTransaction(); //开启事务
        if(empty($is_exist)) { //如果为空，则表示商品不存在,则把该商品写入购物车即可

            $http = getenv('HTTP_REQUEST_URL');
            //改变图片链接，使其可以直接访问
            $goods_info->image =  empty($goods_info->image) ? "" : $http. $goods_info->image;
            //然后把收集到的数据插入数据库:购物车表
            $update = \DB::table('ys_goods_car')->insert([

                'user_id' => $user_id,
                'goods_id' => $request->goods_id,
                'goods_name' => $goods_info->goods_name,
                'goods_price' => $goods_info->price, //商品定价
                'cost_price' => $goods_info->cost_price,//成本价
                'goods_url' => $goods_info->image,
                'number' => 1, //第一次插入，数量为1
                'supplier_id' => $goods_info->supplier_id,//供应商id
                'first_class_id' => $goods_info->first_id,
                'state'=>1,//1选中  0不选中
                'created_at' => \DB::Raw('Now()'),
                'updated_at' => \DB::Raw('Now()'),
            ]);

        }else{ //否则就直接更改数量

            //接着判断加减，每次加减完成之后，需要把该用户的购物车中的商品总金额算出来，返回去
            if($request->symbol == 1){ //加1
                $update = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('goods_id',$request->goods_id)->update(['number'=>$is_exist->number+1,'updated_at'=>\DB::Raw('Now()')]);
            }elseif($request->symbol == 2){ //减1（如果本身就是1个，那么就没办法继续减了）

                if($is_exist->number >= 2){ //只有数量超过2，才能保证减1的情况下，其数量还有1个
                    $update = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('goods_id',$request->goods_id)->update(['number'=>$is_exist->number-1,'updated_at'=>\DB::Raw('Now()')]);
                }else{
                    $update = 1;
                }
            }
        }
        if ($update) {
            \DB::commit();
        }else {
            \DB::rollBack();
        }


       //最后我们计算该用户购物车中所有有效的记录的总价格(分：可返利总金额和不可返利总金额两种)
        $goods_info =  \DB::table('ys_goods_car')->where('user_id',$user_id)->where('state',1)->select('goods_price','number','first_class_id')->get();
        $price = [];
        if(empty($goods_info)){

            $price['return'] = 0;//可支持返利的商品总金额
            $price['no_return'] = 0;//不支持返利的商品总金额

        }else{

               $returns = 0;
               $no_returns = 0;
               foreach($goods_info as $k=>$v){
                   if($v->first_class_id == 4){ //只有一级分类id为4的商品不支持返利，其他的都支持返利
                       $no_returns += ($v->number) * ($v->goods_price);
                   }else{
                       $returns += ($v->number) * ($v->goods_price);
                   }
               }
            $price['return'] = $returns;//可支持返利的商品总金额
            $price['no_return'] = $no_returns;//不支持返利的商品总金额
            $price['return_rule'] = getenv('RETURN_RULE');//返利规则，大于该规则金额才进行返利
        }

        return  $this->respond($this->format($price));
    }


    //3.获取购物车中商品信息
    public function getGoodsCarInfo(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id


        $goods_info = \DB::table('ys_goods_car')->where('user_id',$user_id)
                     ->select('id as car_id','goods_id','goods_name','goods_price','goods_url','number','state','first_class_id','created_at')
                     ->get();

        $result = [];
        $returns = 0;
        $no_returns = 0;
        if(!empty($goods_info)){
            foreach($goods_info as $k=>$v){
                if(($v->state == 1) && ($v->first_class_id == 4)){ //表示该商品是选中状态 ,并且该商品的一级分类id是不支持返利的商品4
                    $no_returns += ($v->number) * ($v->goods_price);
                }elseif(($v->state == 1) && ($v->first_class_id != 4)){
                    $returns += ($v->number) * ($v->goods_price);
                }
            }

            $result['return'] = $returns;//可支持返利的商品总金额
            $result['no_return'] = $no_returns;//不支持返利的商品总金额

        }else{

            $goods_info = [];
            $result['return'] = $returns;//可支持返利的商品总金额
            $result['no_return'] = $no_returns;//不支持返利的商品总金额
            $result['return_rule'] = getenv('RETURN_RULE');//返利规则，大于该规则金额才进行返利
        }

        $result['info'] =  $goods_info;

        return  $this->respond($this->format($result));

    }

    //4.删除购物车中的商品
    public function deleteGoodsCar(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'car_id' => 'required|integer' //购物车id
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //判断该条记录是否存在
        $goods_info = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('id',$request->car_id)->first();
        if(empty($goods_info)){ //未找到该条购物车记录
            return $this->setStatusCode(1043)->respondWithError($this->message);
        }

        \DB::beginTransaction(); //开启事务
        $delete = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('id',$request->car_id)->delete();

        if ($delete) {
            \DB::commit();
            return  $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }


    //5.更改购物车：选中该商品的标志（选中/不选中）
    public function updateGoodsCar(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string',
            'car_id' => 'integer', //购物车id
            'flag' => 'integer|in:1,2' //1 全不选   2全选中
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        if(!empty($request->car_id)){ //如果不为空，则表示更改单个的状态

            //判断该条记录是否存在
            $goods_info = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('id',$request->car_id)->first();
            if(empty($goods_info)){ //未找到该条购物车记录
                return $this->setStatusCode(1043)->respondWithError($this->message);
            }

            if($goods_info->state == 1){
                $state = 0;
            }else{
                $state = 1;
            }
            \DB::beginTransaction(); //开启事务
            $update = \DB::table('ys_goods_car')->where('user_id',$user_id)->where('id',$request->car_id)->update(['state'=>$state,'updated_at'=>\DB::Raw('Now()')]);

            if ($update) {
                \DB::commit();
                return  $this->respond($this->format([],true));
            }else {
                \DB::rollBack();
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }


        }else{ //如果没有购物车id，则说明点击全选或全部选按钮

            if(empty($request->flag)){ //如果没有flag值，则参数错误
               return $this->setStatusCode(9999)->respondWithError($this->message);
            }
            //判断该条记录是否存在
            $car_id_arr = \DB::table('ys_goods_car')->where('user_id',$user_id)->lists('id'); //state: 1表示选中  0 表示不选
            if(empty($car_id_arr)){ //未找到该条购物车记录
                return $this->setStatusCode(1043)->respondWithError($this->message);
            }

            \DB::beginTransaction(); //开启事务
            if($request->flag == 1){//1 全不选
                $update = \DB::table('ys_goods_car')->where('user_id',$user_id)->whereIn('id',$car_id_arr)->update(['state'=>0,'updated_at'=>\DB::Raw('Now()')]);

            }elseif($request->flag == 2){ //  2全选中
                $update = \DB::table('ys_goods_car')->where('user_id',$user_id)->whereIn('id',$car_id_arr)->update(['state'=>1,'updated_at'=>\DB::Raw('Now()')]);
            }

            if ($update) {
                \DB::commit();
                return  $this->respond($this->format([],true));
            }else {
                \DB::rollBack();
                return $this->setStatusCode(9998)->respondWithError($this->message);
            }


        }

    }












}