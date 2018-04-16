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
        		->selectRaw('ys_goods.id,ys_goods.name,sort,sales,ys_goods.state,ys_supplier.name as supplier_id');
                
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
        	$ex_info=\App\GoodsExtendModel::where('goods_id',$val->id)->first();
        	$val['price']=$ex_info?$ex_info->price:0;
        	$val['cost_price']=$ex_info?$ex_info->cost_price:0;
        	$val['num']=$ex_info?$ex_info->num:0;
        	
        }        
        $suppliers=\App\SupplierModel::where('state',1)->get();
        return view('goods.goodslist',['data'=>$paginate,'search'=>$search,'suppliers'=>$suppliers]);
    }
    
    
    
    
    //添加商品前选择分类
    public function GoodsaddSelClass()
    {

    	//列所有分类    	
    	$data=\App\GoodsClassModel::where('first_id',0)->orderby('sort','asc')->get();
    	$suppliers=\App\SupplierModel::where('state',1)->get();
    	return view('goods.goodsaddselclass',['data'=>$data,'suppliers'=>$suppliers]);
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
    	foreach ($spec_data as &$val){    		
    		$val['val_list']=\App\SpecValueModel::where('supplier_id',$request->supplier_id)->where('spec_id',$val->spec_id)->get();    		
    	}


    	$count_spec=count($spec_data);
    	
    	//店内分类
    	$store_class=\App\StoreGoodsClassModel::where('store_id',$request->supplier_id)->orderby('sort','asc')->get();


        return view('goods.goodsadd',['check_spec'=>[],'vie_mode'=>0,'v_list'=>json_encode([]),'spec_data'=>$spec_data,'count_spec'=>$count_spec,'store_class'=>$store_class]);
    }
    //提交商品
    public function GoodsCreate(Request $request)
    {
    	

        $input = Input::except('_token');
        $rules = [
            'name'=> 'required',
            'shipping_price'=> 'required',
            'editorValue'=> 'required',
            'sort'=> 'required',
            'supplier_id'=> 'required',
            'class_id'=> 'required',
            'store_class'=> 'required',
            'spec_name'=> 'required',
            'spec_value'=> 'required',
            'spec'=> 'required',
            
        ];
        $massage = [
            'name.required' =>'商品名称不能为空',
            'shipping_price.required' =>'供应商结算价不能为空',
            'editorValue.required' =>'商品详情不能为空',
            'sort.required' =>'商品排序不能为空',
            'supplier_id.required' =>'供应商不能为空',
            'class_id.required' =>'商品分类不能为空',
            'store_class.required' =>'店内商品分类不能为空',
            'spec_name.required' =>'规格名不能为空',
            'spec_value.required' =>'规格值不能为空',
            'spec.required' =>'价格不能为空',
            
        ];
        $validator = \Validator::make($input,$rules,$massage);

        if($validator->passes()){/**/        	
            $params=array(
                'name'=>$request->name,
            	'shipping_price'=>$request->shipping_price,
                'content'=>$request->editorValue,
                'sort'=>$request->sort,
                'state'=>$request->state>0?$request->state:0,
                'supplier_id'=>$request->supplier_id,
            	'class_id'=>$request->class_id,
            	'store_class'=>$request->store_class,
            	'spec_name'=>serialize($request->spec_name),
            	'spec_value'=>serialize($request->spec_value),
            	
            );
            $res = GoodsModel::create($params);
            
            //dd($res);
            //存储商品扩展信息
            if(count($request->spec_value)==1){
            
            	$spec_val=array_values($request->spec_value);
            	$ii=0;
            	foreach ($spec_val[0] as $k=>$v){
            		$sp_va[$ii][$k]=$v;
            		$sp_params[$ii]=[
            			'goods_id'=>$res->id,
	            		'name'=>$request->name.' '.$v,
	            		'goods_spec'=>serialize($sp_va[$ii]),
	            		'price'=>$request->spec[$k]['price'],
	            		'market_price'=>$request->spec[$k]['market_price'],
	            		'cost_price'=>$request->spec[$k]['cost_price'],
	            		'supplier_price'=>$request->spec[$k]['supplier_price'],
	            		'rebate_amount'=>$request->spec[$k]['rebate_amount'],
	            		'num'=>$request->spec[$k]['num'],
            		];
            		$ii++;
            	}
            }else if(count($request->spec_value)==2){
            	$spec_val=array_values($request->spec_value);
            	$ii=0;
            	foreach ($spec_val[0] as $key=>$val){
            		foreach ($spec_val[1] as $k=>$v){
            			$sp_va[$ii][$key]=$val;
            			$sp_va[$ii][$k]=$v;
            			 
            			$sp_params[$ii]=[
            				'goods_id'=>$res->id,
	            			'name'=>$request->name.' '.$val.' '.$v,
	            			'goods_spec'=>serialize($sp_va[$ii]),
	            			'price'=>$request->spec[$key.$k]['price'],
	            			'market_price'=>$request->spec[$key.$k]['market_price'],
	            			'cost_price'=>$request->spec[$key.$k]['cost_price'],
	            			'supplier_price'=>$request->spec[$key.$k]['supplier_price'],
	            			'rebate_amount'=>$request->spec[$key.$k]['rebate_amount'],
	            			'num'=>$request->spec[$key.$k]['num'],
            			];
            			$ii++;
            		}
            	}
            }
            
            
            $res = \App\GoodsExtendModel::insert($sp_params);
            
            

            
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

    	
        $data = GoodsModel::where('ys_goods.id',$id)
        		->leftjoin('ys_goods_class','ys_goods.class_id','=','ys_goods_class.id')
        		->selectRaw('ys_goods.*,ys_goods_class.first_id')
        		->first();
        $this_goods_spec=unserialize($data->spec_name);
        
        
       //dd($this_goods_spec);
        
        if(count($this_goods_spec)==1){
        	$spec_val=array_values(unserialize($data->spec_value));
        	$check_spec=unserialize($data->spec_value);
        	
        	//dd($check_spec);
        }else if(count($this_goods_spec)==2){
        	
        	
        	//dd(unserialize($data->spec_value));
        	$spec_val=array_values(unserialize($data->spec_value));
        	$check_spec=unserialize($data->spec_value);
        	
        	//dd($check_spec);
        	
        	
     
        	foreach ($spec_val[0] as $key=>$val){
        		foreach ($spec_val[1] as $k=>$v){
        			$sp_va[][$key][$k]=0;

        		}
        	}
        	
        	//dd($sp_va);
        	
        	
        }
        

        $images = GoodsImageModel::where('goods_id',$id)->get();
            	//店内分类
            	
        $extend_info=\App\GoodsExtendModel::where('goods_id',$data->id)
        		->select('goods_spec','market_price','price','cost_price','supplier_price','rebate_amount','num')
        		->get()->toArray();
        $v_list='';
        foreach ($extend_info as $val){
			$a='';
        	$ser_val=unserialize($val['goods_spec']);
        	
        	//dump($ser_val);
        	foreach ($ser_val as $k=>$v){
        		$a.=$k;        		
        	}
        	$v_list[$a]=$val;
        }
        $v_list=json_encode($v_list);
       // dd($v_list);
        //value[14]['market_price']
//         "spec_id" => 8
//         "spec_name" => "重量"
//         "val_list=[ 
//             	"id" => 4
//                 "name" => "普通包装"
//                 "spec_id" => 9
//                 "supplier_id" => 1
            
//             ];
        
       // dd($extend_info);
            	
        
        $spec_data=\App\GoodsClassModel::where('ys_goods_class.id',$data->class_id)
        ->leftjoin('ys_goods_type','ys_goods_type.id','=','ys_goods_class.goods_type')
        ->leftjoin('ys_type_spec','ys_type_spec.type_id','=','ys_goods_type.id')
        ->leftjoin('ys_goods_spec','ys_goods_spec.id','=','ys_type_spec.spec_id')
        ->select('ys_goods_spec.id as spec_id','ys_goods_spec.name as spec_name')
        ->get();
        foreach ($spec_data as &$val){
        	$val['spec_name']=$this_goods_spec[$val['spec_id']];
        	$val['val_list']=\App\SpecValueModel::where('supplier_id',$data->supplier_id)->where('spec_id',$val->spec_id)->get();
        }
         
        
        //dd($spec_data);
        // dd($v_list);
        $count_spec=count($spec_data);
        
        //店内分类
        $store_class=\App\StoreGoodsClassModel::where('store_id',$data->supplier_id)->orderby('sort','asc')->get();
         
             //print_r($v_list);
        return view('goods.goodsadd',['check_spec'=>$check_spec,'vie_mode'=>1,'v_list'=>$v_list,'spec_data'=>$spec_data,'count_spec'=>$count_spec,'data'=>$data,'images'=>$images,'store_class'=>$store_class]);
    }

    //编辑商品保存
    public function Goodssave(Request $request)
    {

    	
        $input = Input::except('_token');
    	$rules = [
	    	'name'=> 'required',
	    	'shipping_price'=> 'required',
	    	'editorValue'=> 'required',
	    	'sort'=> 'required',
	    	'supplier_id'=> 'required',
	    	'class_id'=> 'required',
	    	'store_class'=> 'required',
	    	'spec_name'=> 'required',
	    	'spec_value'=> 'required',
	    	'spec'=> 'required',
    	
    	];
    	$massage = [
	    	'name.required' =>'商品名称不能为空',
	    	'shipping_price.required' =>'供应商结算价不能为空',
	    	'editorValue.required' =>'商品详情不能为空',
	    	'sort.required' =>'商品排序不能为空',
	    	'supplier_id.required' =>'供应商不能为空',
	    	'class_id.required' =>'商品分类不能为空',
	    	'store_class.required' =>'店内商品分类不能为空',
	    	'spec_name.required' =>'规格名不能为空',
	    	'spec_value.required' =>'规格值不能为空',
	    	'spec.required' =>'价格不能为空',
    	
    	];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){

            $params=array(
                'name'=>$input['name'],
                'shipping_price'=>$input['shipping_price'],
                'content'=>$input['editorValue'],
                'sort'=>$input['sort'],
                'state'=>$request->state>0?$request->state:0,
            	'supplier_id'=>$input['supplier_id'],
            	'class_id'=>$input['class_id'],            		
            	'store_class'=>$input['store_class'],
            	'spec_name'=>serialize($input['spec_name']),
            	'spec_value'=>serialize($input['spec_value']),
            );
            
            
            $res = GoodsModel::where('id',$input['id'])->update($params);
            
            
            
            
            
            
            //存储商品扩展信息
            if(count($request->spec_value)==1){
            	 
            	$spec_val=array_values($request->spec_value);
            	$ii=0;
            	foreach ($spec_val[0] as $k=>$v){
            		$sp_va[$ii][$k]=$v;
            		$sp_params[$ii]=[
            		'goods_id'=>$input['id'],
            		'name'=>$request->name.' '.$v,
            		'goods_spec'=>serialize($sp_va[$ii]),
            		'price'=>$request->spec[$k]['price'],
            		'market_price'=>$request->spec[$k]['market_price'],
            		'cost_price'=>$request->spec[$k]['cost_price'],
            		'supplier_price'=>$request->spec[$k]['supplier_price'],
            		'rebate_amount'=>$request->spec[$k]['rebate_amount'],
            		'num'=>$request->spec[$k]['num'],
            		];
            		$ii++;
            	}
            }else if(count($request->spec_value)==2){
            	$spec_val=array_values($request->spec_value);
            	$ii=0;
            	foreach ($spec_val[0] as $key=>$val){
            		foreach ($spec_val[1] as $k=>$v){
            			$sp_va[$ii][$key]=$val;
            			$sp_va[$ii][$k]=$v;
            			 
            			$sp_params[$ii]=[
            			'goods_id'=>$input['id'],
            			'name'=>$request->name.' '.$val.' '.$v,
            			'goods_spec'=>serialize($sp_va[$ii]),
            			'price'=>$request->spec[$key.$k]['price'],
            			'market_price'=>$request->spec[$key.$k]['market_price'],
            			'cost_price'=>$request->spec[$key.$k]['cost_price'],
            			'supplier_price'=>$request->spec[$key.$k]['supplier_price'],
            			'rebate_amount'=>$request->spec[$key.$k]['rebate_amount'],
            			'num'=>$request->spec[$key.$k]['num'],
            			];
            			$ii++;
            		}
            	}
            }
             
            $res = \App\GoodsExtendModel::where('goods_id',$input['id'])->delete();
            $res = \App\GoodsExtendModel::insert($sp_params);
            
            
            
            
            
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
