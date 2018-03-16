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



    public function bannerList(Request $request)
    {


//        $banner_list = \DB::table('ys_banner_manage')->select('id','img_url','sort')->orderBy('sort','asc')->get();







//        //搜索供应商，商品名，商品状态
//        $search=[];
//        $data = GoodsModel::orderBy('sort','asc')->orderBy('created_at','desc')
//            ->join('ys_supplier','ys_goods.supplier_id','=','ys_supplier.id')
//            ->selectRaw('ys_goods.id,ys_goods.name,num,sort,price,cost_price,supplier_price,sales,ys_goods.state,ys_supplier.name as supplier_id');
//        if($request->name !=''){
//            $data->where('ys_goods.name','like','%'.$request->name.'%');
//            $search['name']=$request->name;
//        }
//        if (isset($request->state) && $request->state != -1){
//            $data->where('ys_goods.state',$request->state);
//            $search['state']=$request->state;
//        }
//        if ($request->supplier != ''){
//            $data->where('ys_goods.supplier_id',$request->supplier);
//            $search['supplier']=$request->supplier;
//        }
//        $paginate = $data->paginate(10);
//        $state_arr=[0=>'下架',1=>'正常'];
//        foreach ($paginate as $val){
//            $val['state']=$state_arr[$val['state']];
//
//        }
//        $suppliers=\App\SupplierModel::where('state',1)->get();
//        return view('goods.goodslist',['data'=>$paginate,'search'=>$search,'suppliers'=>$suppliers]);
//
//


    }








}