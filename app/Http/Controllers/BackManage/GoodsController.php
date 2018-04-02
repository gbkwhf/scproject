<?php

namespace App\Http\Controllers\BackManage;

use App\GoodsModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\GoodsImageModel;

class GoodsController extends Controller
{
    //商品列表
    public function Goodslist(Request $request)
    {
    	//搜索供应商，商品名，商品状态
    	$search=[];
        $data = GoodsModel::orderBy('sort','asc')->orderBy('created_at','desc')
        		->join('ys_supplier','ys_goods.supplier_id','=','ys_supplier.id')
        		->selectRaw('ys_goods.id,ys_goods.name,num,sort,price,cost_price,supplier_price,sales,ys_goods.state,ys_supplier.name as supplier_id');        
        if($request->name !=''){
            $data->where('ys_goods.name','like','%'.$request->name.'%');
            $search['name']=$request->name;
        }
        if (isset($request->state) && $request->state != -1){
            $data->where('ys_goods.state',$request->state);
            $search['state']=$request->state;
        }
        if ($request->supplier != ''){
        	$data->where('ys_goods.supplier_id',$request->supplier);
        	$search['supplier']=$request->supplier;
        }   
        $paginate = $data->paginate(10);
        $state_arr=[0=>'下架',1=>'正常'];
        foreach ($paginate as $val){
        	$val['state']=$state_arr[$val['state']];
        	
        }        
        $suppliers=\App\SupplierModel::where('state',1)->get();
        return view('goods.goodslist',['data'=>$paginate,'search'=>$search,'suppliers'=>$suppliers]);
    }
    
    
    
    
    //添加商品前选择分类
    public function GoodsaddSelClass()
    {

    	//列所有分类    	
    	$data=\App\GoodsClassModel::where('first_id',0)->orderby('sort','asc')->get();
    	return view('goods.goodsaddselclass',['data'=>$data]);
    }
    
    
    
    //添加
    public function Goodsadd(Request $request)
    {    	
    	
    	$spec_data=\App\GoodsClassModel::where('ys_goods_class.id',$request->class_id)
    			->leftjoin('ys_goods_type','ys_goods_type.id','=','ys_goods_class.goods_type')
    			->leftjoin('ys_type_spec','ys_type_spec.type_id','=','ys_goods_type.id')
    			->leftjoin('ys_goods_spec','ys_goods_spec.id','=','ys_type_spec.spec_id')
    			->select('ys_goods_spec.id as spec_id','ys_goods_spec.name as spec_name')
    			->get();
    	
    	//dd($spec_data);
    	
    	
    	//dd($request->class_id);
    	//所有供应商
    	$suppliers=\App\SupplierModel::where('state',1)->get();    	
        return view('goods.goodsadd',['suppliers'=>$suppliers,'spec_data'=>$spec_data]);
    }
    //提交商品
    public function GoodsCreate(Request $request)
    {

        $input = Input::except('_token');
        $rules = [
            'name'=> 'required',
            'num'=> 'required|regex:/^[0-9]{1,9}$/',
            'price'=> 'required',
            'cost_price'=> 'required',
            'supplier_price'=> 'required',
            'editorValue'=> 'required',
            'sort'=> 'required',
            'supplier_id'=> 'required',
            'class_id'=> 'required',
            
        ];
        $massage = [
            'name.required' =>'商品名称不能为空',
            'num.required' =>'商品库存不能为空',
            'num.regex' =>'商品库存必须大于0，且长度小于9位',
            'price.required' =>'销售价不能为空',
            'cost_price.required' =>'成本价不能为空',
            'supplier_price.required' =>'供应商结算价不能为空',
            'editorValue.required' =>'商品详情不能为空',
            'sort.required' =>'商品排序不能为空',
            'supplier_id.required' =>'供应商不能为空',
            'class_id.required' =>'商品分类不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);

        if($validator->passes()){/**/        	
            $params=array(
                'name'=>$request->name,
                'num'=>$request->num,
                'price'=>$request->price,
            	'cost_price'=>$request->cost_price,
            	'supplier_price'=>$request->supplier_price,
                'content'=>$request->editorValue,
                'sort'=>$request->sort,
                'state'=>$request->state>0?$request->state:0,
                'supplier_id'=>$request->supplier_id,
            	'class_id'=>$request->class_id,
            );
            $res = GoodsModel::create($params);
            if ($request->hasFile('image')){//图片上传
            	$image=[];
            	foreach ($request->file('image') as $file){
            		$file_name='';
            		if(!empty($file)){
            			$up_res=uploadPic($file);    		
            			$file_name=$up_res;
            			$img_params[]=[
	            			'goods_id'=>$res->id,
	            			'image'=>$file_name,
            			];            			
            		}           		 
            	}
            	\App\GoodsImageModel::insert($img_params);
            }
            if($res){
                return redirect('goodslist');
            }else{
                return back() -> with('errors','数据填充失败');
            }
        }else{
            return back() -> withErrors($validator);
        }

    }
    //编辑商品
    public function GoodsEdit($id)
    {
    	$suppliers=\App\SupplierModel::where('state',1)->get();    	 
        $data = GoodsModel::where('ys_goods.id',$id)
        		->leftjoin('ys_goods_class','ys_goods.class_id','=','ys_goods_class.id')
        		->selectRaw('ys_goods.*,ys_goods_class.first_id')
        		->first();
        $images = GoodsImageModel::where('goods_id',$id)->get();
        $goods_class=\DB::table('ys_goods_class')->where('first_id',$data->first_id)->get();        
        return view('goods.goodsadd',['data'=>$data,'images'=>$images,'suppliers'=>$suppliers,'goods_class'=>$goods_class]);
    }

    //编辑商品保存
    public function Goodssave(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'name'=> 'required',
            'num'=> 'required|regex:/^[0-9]{1,9}$/',
            'price'=> 'required',
            'cost_price'=> 'required',
            'supplier_price'=> 'required',
            'editorValue'=> 'required',
            'sort'=> 'required',
            'supplier_id'=> 'required',
            'class_id'=> 'required',
            
        ];
        $massage = [
            'name.required' =>'商品名称不能为空',
            'num.required' =>'商品库存不能为空',
            'num.regex' =>'商品库存必须大于0，且长度小于9位',
            'price.required' =>'销售价不能为空',
            'cost_price.required' =>'成本价不能为空',
            'supplier_price.required' =>'供应商结算价不能为空',
            'editorValue.required' =>'商品详情不能为空',
            'sort.required' =>'商品排序不能为空',
            'supplier_id.required' =>'供应商不能为空',
            'class_id.required' =>'商品分类不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){

            $params=array(
                'name'=>$input['name'],
                'num'=>$input['num'],
                'content'=>$input['editorValue'],
                'sort'=>$input['sort'],
                'price'=>$input['price'],
                'cost_price'=>$input['cost_price'],
            	'supplier_price'=>$input['supplier_price'],
                'state'=>$request->state>0?$request->state:0,
            	'supplier_id'=>$input['supplier_id'],
            	'class_id'=>$input['class_id'],
            );
            $res = GoodsModel::where('id',$input['id'])->update($params);
            
            if ($request->hasFile('image')){//图片上传
            	$image=[];
            	foreach ($request->file('image') as $file){
            		$file_name='';
            		if(!empty($file)){
            			$up_res=uploadPic($file);
            			$file_name=$up_res;
            			$img_params[]=[
	            			'goods_id'=>$input['id'],
	            			'image'=>$file_name,
            			];
            		}
            	}   
            	\App\GoodsImageModel::where('goods_id',$input['id'])->delete();           	
            	\App\GoodsImageModel::insert($img_params);
            }
            
            if($res === false){
                return back() -> with('errors','数据更新失败');
            }else{
                return redirect('goodslist');
            }
        }else{
            return back() -> withErrors($validator);
        }
    }
    //删除商品
    public function Goodsdel($id)
    {
        GoodsModel::where('id',$id)->delete();
        return redirect('goodslist');
    }
}
