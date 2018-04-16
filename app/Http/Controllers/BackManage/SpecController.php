<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29
 * Time: 10:46
 */

namespace App\Http\Controllers\BackManage;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


/**
 * Class SpecController
 * @package App\Http\Controllers\BackManage
 * 商品规格管理
 */
class SpecController extends Controller{




    //商品规格列表
    public function specList(Request $request)
    {

        $spec_list = \DB::table('ys_goods_spec')->select('id','name')->orderBy('id','asc')->paginate(10);

        $num  = \DB::table('ys_goods_spec')->select('id','name')->orderBy('id','asc')->get();

        return view('speclist',['data'=>$spec_list,'num'=>$num]);

    }

    //添加
    public function specAdd()
    {

        return view('specAdd');
    }

    //保存
    public function specSave(Request $request){



        if(!empty($request->spec)){

            $res  = \DB::table('ys_goods_spec')->insert([

                'name'=>$request->spec
            ]);

            if($res){
                return redirect('spec/list');
            }else{
                return back() -> with('errors','数据填充失败');
            }

        }else{

            return back() -> with('errors','规格名称必填');
        }

    }




    //编辑
    public function specEdit($id){

        $spec_info = \DB::table('ys_goods_spec')->select('id','name')->where('id',$id)->first();

        return view('specEdit',['data'=>$spec_info]);

    }
    //编辑处理逻辑
    public function specEditSave(Request $request){


        if(!empty($request->spec) && !empty($request->id)){

            $res  = \DB::table('ys_goods_spec')->where('id',$request->id)->update([

                'name'=>$request->spec
            ]);

            if($res){
                return redirect('spec/list');
            }else{
                return back() -> with('errors','数据更改失败');
            }

        }else{

            return back() -> with('errors','规格名称必填');
        }

    }

    //删除
    public function specDel($id){

        \DB::table('ys_goods_spec')->where('id',$id)->delete();
        return redirect('spec/list');

    }








}