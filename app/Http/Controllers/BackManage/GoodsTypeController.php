<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29
 * Time: 11:47
 */

namespace App\Http\Controllers\BackManage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


/**
 * Class GoodsTypeController
 * @package App\Http\Controllers\BackManage
 * 商品类型管理
 */
class GoodsTypeController extends Controller{


    //商品类型列表
    public function goodsTypeList(Request $request)
    {


        $goods_type = \DB::table('ys_goods_type')->select('id','name as goods_type_name','sort')->orderBy('sort','asc')->paginate(10);
        $num = \DB::table('ys_goods_type')->select('id','name as goods_type_name','sort')->get();

        $arr = [];
        if(!empty($goods_type)){
            foreach($goods_type as $k=>$v){
                $goods_type[$k]->others=[];
                array_push($arr,$v->id);
            }

            $spec_arr = \DB::table('ys_type_spec as a')
                ->leftjoin('ys_goods_spec as b','a.spec_id','=','b.id')
                ->select('a.type_id','a.spec_id','b.name as spec_name')
                ->whereIn('a.type_id',$arr)
                ->get();

            if(!empty($spec_arr)){
                foreach($goods_type as $k=>$v){

                    foreach($spec_arr as $kk=>$vv){

                         if($v->id == $vv->type_id){
                             array_push($goods_type[$k]->others,$vv);
                         }
                    }
                }
            }
        }
        return view('goodsTypeList',['data'=>$goods_type,'num'=>$num]);
    }

    //添加商品类型
    public function goodsTypeAdd()
    {
        $spec_arr = \DB::table('ys_goods_spec')->select('id','name')->get();
        return view('goodsTypeAdd',['data'=>$spec_arr]);
    }


    //保存商品类型
    public function goodsTypeSave(Request $request){


        //[goodsTypeName] => 内衣 [sort] => 255 [spec] => 5 [id] => [spec_arr] => 2,3,5
        \DB::beginTransaction(); //开启事务

        $res1 = \DB::table('ys_goods_type')->insertGetId([

               'name'=>$request->goodsTypeName,
               'sort'=>$request->sort
        ]);

        if(!empty($request->spec_arr)){

            $arr = explode(",",$request->spec_arr); //多选框选中的规格id

            $tmp = [];
            foreach($arr as $v){

                array_push($tmp, [
                    'type_id'=>$res1,
                    'spec_id'=>$v
                ]);
            }

          $res2 = \DB::table('ys_type_spec')->insert($tmp);

        }else{
           $res2=1;
        }


        if ($res1 && $res2) {
            \DB::commit();
            return redirect('goods/type/list');
        }else {
            \DB::rollBack();
            return back() -> with('errors','数据填充失败');
        }

    }


   //编辑商品类型
    public function goodsTypeEdit($id){


        $goods_type = \DB::table('ys_goods_type')->select('id','name as type_name','sort')->where('id',$id)->first();
        $spec_arr = \DB::table('ys_type_spec as a')
                    ->leftjoin('ys_goods_spec as b','a.spec_id','=','b.id')
                    ->where('a.type_id',$id)
                    ->lists('b.id');

        $spec_arr_all = \DB::table('ys_goods_spec')->select('id','name')->get();

        return view('goodsTypeEdit',['data'=>$goods_type,'spec'=>$spec_arr,'spec_all'=>$spec_arr_all]);


    }

    //编辑商品类型保存
    public function goodsTypeEditSave(Request $request)
    {


        $have = \DB::table('ys_type_spec')->where('type_id',$request->id)->get();

        $is_update = \DB::table('ys_goods_type')->where('id',$request->id)->where('name',$request->goodsTypeName)->where('sort',$request->sort)->first();


        \DB::beginTransaction(); //开启事务

        if(empty($is_update)){ //如果提交的数据在数据库匹配不到，说明有新数据，需要更新，否则不管

            $res1 = \DB::table('ys_goods_type')->where('id',$request->id)->update([

                'name'=>$request->goodsTypeName,
                'sort'=>$request->sort
            ]);

        }else{

            $res1 = 1;
        }


        if(!empty($have)){
            $res3 =  \DB::table('ys_type_spec')->where('type_id',$request->id)->delete();
        }else{
            $res3 = 1;
        }


        if(!empty($request->spec_arr)){

            $arr = explode(",",$request->spec_arr); //多选框选中的规格id

            $tmp = [];
            foreach($arr as $v){

                array_push($tmp, [
                    'type_id'=>$request->id,
                    'spec_id'=>$v
                ]);
            }

            $res2 = \DB::table('ys_type_spec')->insert($tmp);

        }else{
            $res2=1;
        }


        if ($res1 && $res2 && $res3) {
            \DB::commit();
            return redirect('goods/type/list');
        }else {
            \DB::rollBack();
            return back() -> with('errors','数据更改失败');
        }


    }


   //删除商品类型
    public function goodsTypeDel($id){


        $have = \DB::table('ys_type_spec')->where('type_id',$id)->get();

        \DB::beginTransaction(); //开启事务


        $res1 = \DB::table('ys_goods_type')->where('id',$id)->delete();

        if(!empty($have)){
            $res2 =  \DB::table('ys_type_spec')->where('type_id',$id)->delete();
        }else{
            $res2 = 1;
        }

        if ($res1 && $res2) {
            \DB::commit();
            return redirect('goods/type/list');
        }else {
            \DB::rollBack();
            return back() -> with('errors','数据删除失败');
        }
    }









}