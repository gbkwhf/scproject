<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 15:49
 */

namespace App\Http\Controllers\BackManage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


/**
 * Class BannerController
 * @package App\Http\Controllers\BackManage
 * Banner轮播图管理
 */
class BannerController   extends Controller{



    //banner列表
    public function bannerList(Request $request)
    {

        $banner_list = \DB::table('ys_banner_manage')->select('id','img_url','sort')->orderBy('sort','asc')->paginate(6);

        $num  = \DB::table('ys_banner_manage')->select('id','img_url','sort')->orderBy('sort','asc')->get();

        return view('bannerlist',['data'=>$banner_list,'num'=>$num]);

    }


    //添加
    public function bannerAdd()
    {

        return view('banneradd');
    }

    //保存
    public function bannerSave(Request $request){

            if ($request->hasFile('image')){//图片上传

                $up_res=uploadPic($request->file('image')[0]);
                $file_name=$up_res;

                $res  = \DB::table('ys_banner_manage')->insert([
                    'img_url'=>$file_name,
                    'sort'=>$request->sort
                ]);


                if($res){
                    return redirect('banner/list');
                }else{
                    return back() -> with('errors','数据填充失败');
                }
        }else{

             return back() -> with('errors','图片必传');
        }


    }


    //编辑
    public function bannerEdit($id)
    {

        $banner_info = \DB::table('ys_banner_manage')->select('id','img_url','sort')->where('id',$id)->first();

        return view('bannerEdit',['data'=>$banner_info]);


    }

    //保存编辑
    public function bannerEditSave(Request $request){



        if ($request->hasFile('image')){//图片上传

            $up_res=uploadPic($request->file('image')[0]);
            $file_name=$up_res;

            $res  = \DB::table('ys_banner_manage')->where('id',$request->edit_id)->update([
                'img_url'=>$file_name,
                'sort'=>$request->sort
            ]);


        }else{

            $res  = \DB::table('ys_banner_manage')->where('id',$request->edit_id)->update([

                        'sort'=>$request->sort
                    ]);

        }

        if($res){
            return redirect('banner/list');
        }else{
            return back() -> with('errors','数据填充失败');
        }



    }

    //删除
    public function bannerDel($id){

        \DB::table('ys_banner_manage')->where('id',$id)->delete();
        return redirect('banner/list');
    }





}