<?php

namespace App\Http\Controllers\Baseuser;
use Acme\Exceptions\ValidationErrorException;
use App\GoodsModel;
use App\Member;
use App\Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //商品列表
    public function Goods_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'home' => 'string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        if (!empty($request->ss)){//如果session存在
            $session = Session::where('session',$request->ss)->first();
            if ($session){
                $user_id = $this->getUserIdBySession($request->ss); //获取用户id
                $member = Member::where('user_id', $user_id)->first();
                $member_grade = $member['grade']; //获取用户等级
            }else{
                $member_grade = 1; //获取用户等级
            }
        }else{
            $member_grade = 1; //获取用户等级
        }
        //var_dump($member_grade);
        $myprice = array(   //用商品价格重组数组
            1 =>'price',
            2 =>'price_grade1',
            3 =>'price_grade2',
            4 =>'price_grade3',
            5 =>'price_grade4',
        );
        
        $price = $myprice[$member_grade]; //用户会员等级显示的价格
        if ($request->home == 1){
            $start = $request->page <= 1 ? 0 : (($request->page) - 1) * 12;//分页
        }else{
            $start = $request->page <= 1 ? 0 : (($request->page) - 1) * 10;//分页
        }

        $res_goods = GoodsModel::select('*')
            ->where('state', 1)->orderBy('sort','asc')
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
        if ($request->home == 1){
            $res_info = GoodsModel::select('*')
                ->where('state', 1)->orderBy('sort','asc')
                ->orderBy('created_at', 'desc')
                ->skip($start)->take(12)
                ->get()->toArray();
        }else{
            $res_info = GoodsModel::select('*')
                ->where('state', 1)->orderBy('sort','asc')
                ->orderBy('created_at', 'desc')
                ->skip($start)->take(10)
                ->get()->toArray();
        }

        foreach ($res_info as $val){
            $val['price']=$val[$price];
            //dd($val[$price]);
        }

        $data=array();

        if ($request->home == 1){
            for ($i=0;$i<count($res_info);$i++){
                $data['list'][] = array(/**/
                    'id'=>$res_info[$i]['id'],
                    'name'=>$res_info[$i]['name'],
                    'image'=>$res_info[$i]['image'],
                    'state'=>$res_info[$i]['state'],
                    'original_price'=>$res_info[$i]['price'],
                    'price'=>$res_info[$i][$price],
                    'sales'=>$res_info[$i]['sales'],
                    'sort'=>$res_info[$i]['sort'],
                );
            }
            $data['count'] = count($res_goods);
        }else{
            for ($i=0;$i<count($res_info);$i++){
                $data[] = array(/**/
                    'id'=>$res_info[$i]['id'],
                    'name'=>$res_info[$i]['name'],
                    'image'=>$res_info[$i]['image'],
                    'state'=>$res_info[$i]['state'],
                    'original_price'=>$res_info[$i]['price'],
                    'price'=>$res_info[$i][$price],
                    'sales'=>$res_info[$i]['sales'],
                );
            }
        }

        return $this->respond($this->format($data));
    }

    //商品详情
    public function Goods_info(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'gid' => 'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        if (isset($request->ss)){
            $session = Session::where('session',$request->ss)->first();
            if ($session){
                    $user_id = $this->getUserIdBySession($request->ss); //获取用户id
                    $member = Member::where('user_id',$user_id)->first();
                    $member_grade = $member['grade']; //获取用户等级
                }else{
                    $member_grade = '1'; //获取用户等级
                }
            }else{
            $member_grade = '1'; //获取用户等级
            }

        $res_info = GoodsModel::where('state',1)
            ->where('id',$request->gid)
            ->first();
        if (!$res_info){
        	return $this->setStatusCode(6103)->respondWithError($this->message);        	 
        }
        
        if ($member_grade == 1){ //根据用户等级获取商品价格
                $price = $res_info['price'];
        }elseif ($member_grade == 2){
                $price = $res_info['price_grade1'];
        }elseif ($member_grade == 3){
            $price = $res_info['price_grade2'];
        }elseif ($member_grade == 4){
            $price = $res_info['price_grade3'];
        }elseif ($member_grade == 5){
            $price = $res_info['price_grade4'];
        }
        
        $res = array(   //将商品信息写入数组
            'id' => $res_info['id'],
            'name' => $res_info['name'],
            'content' =>formatContent($res_info['content']),
            'image' => $res_info['image'],
            'num' => $res_info['num'],
            'price' => $price,
            'sales' => $res_info['sales']
        );
        if($res_info['id']){
            return $this->respond($this->format($res));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }

}
