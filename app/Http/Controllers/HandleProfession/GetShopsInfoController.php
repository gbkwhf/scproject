<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 16:14
 */

namespace App\Http\Controllers\HandleProfession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


class GetShopsInfoController extends Controller{

    //1.获取商品二级分类
    public function getSecondClass($first_id)
    {

        if(($first_id>4) || ($first_id < 1)) return $this->setStatusCode(9999)->respondWithError($this->message);

        $data = \DB::table('ys_goods_class')->select('id as second_id','name as second_name')->where('first_id',$first_id)->orderBy('sort','asc')->get();

        $result = empty($data) ? [] : $data;
        return  $this->respond($this->format($result));

    }

    //2.根据二级分类id获取商品列表
    public function getCommodityLists($second_id)
    {

        if($second_id < 1) return $this->setStatusCode(9999)->respondWithError($this->message);

        $data = \DB::table('ys_goods as a')->leftjoin('ys_goods_image as b','a.id','=','b.goods_id')
                ->select('a.id as goods_id','a.name as goods_name','a.num','a.price','a.sales','b.image')
                ->where('a.class_id',$second_id)
                ->where('a.state',1) //0下架1上架
                ->groupBy('a.id')
                ->orderBy('a.sort','asc')
                ->orderBy('a.id','asc')
                ->get();

        $result = empty($data) ? [] : $data;

       //改变图片链接，使其可以直接访问
       if(!empty($result)){

           foreach($result as $k=>$v){


           }
       }

        return  $this->respond($this->format($result));
    }

    //3.根据商品id获取商品详情
    public function getCommodityInfo($goods_id)
    {

        if($goods_id < 1) return $this->setStatusCode(9999)->respondWithError($this->message);

        //商品详情
        $goods = \DB::table('ys_goods')->select('id as goods_id','name as goods_name','num','price','sales','content','sales as img_url')
                  ->where('state',1) //0下架1上架
                  ->where('id',$goods_id)->first();

        $goods = empty($goods) ? [] : $goods;

        if(!empty($goods)){
            //商品配图
            $images = \DB::table('ys_goods_image')->where('goods_id',$goods_id)->select('id as img_id','image')->get();

            $images = empty($images) ? [] : $images;

            //改变图片链接，使其可以直接访问
            if(!empty($images)){

                foreach($images as $k=>$v){


                }

            }

            $goods->img_url = $images;

        }

        //商品评价内容（暂时不做）

        return  $this->respond($this->format($goods));
    }





}