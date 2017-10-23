<?php

namespace App\Http\Controllers\BackManage;

use App\GoodsModel;
use App\Member;
use App\MessageModel;
use App\OrderModel;
use App\OrdersModel;
use App\RecollectionCodeModel;
use App\Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //订单列表
    public function Orderlist(Request $request)
    {
        $a=[];
        $data = OrderModel::join('stj_goods','stj_order.goods_id','=','stj_goods.id')
            ->select('stj_order.id','stj_order.num','stj_order.receive_name','stj_order.state','stj_order.created_at','stj_goods.name','stj_order.express','stj_order.tracking_number')
            ->orderBy('stj_order.created_at','desc');
        if($request->name !=''){
            $data->where('stj_goods.name','like','%'.$request->name.'%');
            $a['name']=$request->name;
        }
        if ($request->state != ''){ //订单状态
            $data->where('stj_order.state',$request->state);
            $a['state']=$request->state;
        }
        if ($request->start != ''){//起始时间
            $start = $request->start.' 00:00:00';
            $data->where('stj_order.created_at','>=',$start);
            $a['start']=$request->start;
        }
        if ($request->end != ''){//结束时间
            $end = $request->end.' 59:59:59';
            $data->where('stj_order.created_at','<=',$end);
            $a['end']=$request->end;
        }
        if ($request->model != ''){// 收货人电话
            $data->where('stj_order.receive_phone','like','%'.$request->model.'%');
            $a['model']=$request->model;
        }
        $paginate = $data->paginate(10);
        session(['search'=>$a]);
        return view('order.orderlist',['data'=>$paginate]);
    }

    //订单发货
    public function Deliver($id)
    {
        $data_order = OrderModel::where('id',$id)->first();
        $data_goods = GoodsModel::where('id',$data_order['goods_id'])->first();
        $data_user = Member::where('user_id',$data_order['user_id'])->first();
        $goods_name = $data_goods['name'];
        $user_name = $data_user['name'];
        $user_id = $data_order['user_id'];
        $params=array(
            'id'=>$data_order['id'],
            'goods_name'=>$goods_name,
            'user_name'=>$user_name,
            'num'=>$data_order['num'],
            'receive_name'=>$data_order['receive_name'],
            'receive_phone'=>$data_order['receive_phone'],
            'receive_address'=>$data_order['receive_address'],
        );
        $date = array(
            'user_id'=>$user_id,
            'created_at'=>date('Y-m-d H:i:s',time()),
            'content'=>'您购买的'.$goods_name.'商品已发货',
        );
        MessageModel::insert($date);
        return view('order.orderdeliver',['data'=>$params]);
    }

    //保存发货订单
    public function Ordersave(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'express'=> 'required',
            'tracking_number'=> 'required',
        ];
        $massage = [
            'express.required' =>'请选择快递公司',
            'tracking_number.required' =>'快递单号不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){
            $params=array(
                'express'=>$request->express,
                'tracking_number'=>$request->tracking_number,
                'state'=>'2',
            );
            $res = OrderModel::where('id',$request->id)->update($params);

            if($res === false){
                return back() -> with('errors','数据更新失败');
            }else{
                return redirect('orderlist');
            }
        }else{
            return back() -> withErrors($validator);
        }
    }

    public function Excel()
    {

        $search = session('search');
        $data = OrderModel::join('stj_goods','stj_order.goods_id','=','stj_goods.id')
            ->select('stj_order.id','stj_order.state','stj_goods.name','stj_order.num','stj_order.receive_name','stj_order.receive_phone','stj_order.receive_address','stj_order.express','stj_order.tracking_number','stj_order.created_at')
            ->orderBy('stj_order.id','asc');
        if(isset($search['name'])){
            $data->where('stj_goods.name','like','%'.$search['name'].'%');
        }
        if (isset($search['state'])){ //订单状态
            $data->where('stj_order.state',$search['state']);
        }
        if (isset($search['start'])){//起始时间
            $data->where('stj_order.created_at','>=',$search['start']);
        }
        if (isset($search['end'])){//结束时间
            $data->where('stj_order.created_at','<=',$search['end']);
        }
        if (isset($search['model'])){// 收货人电话
            $data->where('stj_order.receive_phone','like','%'.$search['model'].'%');
        }
        $state =array(
            '0'=>'未付款',
            '1'=>'待发货',
            '2'=>'已发货',
        );
        $express =array(
            '1'=>'顺丰速递',
            '2'=>'韵达快递',
            '3'=>'中通快递',
            '4'=>'申通速递',
            '5'=>'天天快递',
            '6'=>'宅急送',
        );
        $a = $data->get()->toArray();
        for ($i=0;$i<count($a);$i++){
            $state_id = $a[$i]['state'];
            $state_name = $state[$state_id];//订单状态
            $a[$i]['state'] = $state_name;

            $express_id = $a[$i]['express'];
            if ($express_id !=''){
                $express_name = $express[$express_id];//快递公司
                $a[$i]['express'] = $express_name;
            }else{
                $a[$i]['express'] =' 未发货';
            }
        }
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="order.csv"');
        header('Cache-Control: max-age=0');

        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息
        $head = array('订单id','订单状态','商品名称','购买数量','收货人','收货电话','收货地址','快递公司','快递单号','订单生成时间');
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }

        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        foreach ($a as $key => $val) {
            foreach($val as $k=>$v){
                $new[$k] = iconv('utf-8', 'gbk', $v);
            }
            fputcsv($fp, $new);
        }
    }

    public function Orderinfo($id)
    {
        $express =array(
            '1'=>'顺丰速递',
            '2'=>'韵达快递',
            '3'=>'中通快递',
            '4'=>'申通速递',
            '5'=>'天天快递',
            '6'=>'宅急送',
        );
        $order = OrderModel::join('stj_goods','stj_order.goods_id','=','stj_goods.id')
            ->select('stj_order.*','stj_goods.name')
            ->where('stj_order.id',$id)->first();
        $member = Member::where('user_id',$order['user_id'])->first();

        if ($order['express'] == '' ){
            $express = '';
        }else{
            $express = $express[$order['express']];
        }
        $params = array(
            'name'=>$order['name'],
            'receive_name'=>$order['receive_name'],
            'receive_phone'=>$order['receive_phone'],
            'receive_address'=>$order['receive_address'],
            'num'=>$order['num'],
            'by_name'=>$member['name'],
            'express'=>$express,
            'tracking_number'=>$order['tracking_number'],
        );
        return view('order.orderinfo',['data'=>$params]);
    }
}
