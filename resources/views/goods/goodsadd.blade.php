<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
@section('main-content')

<style>
	.val_class{width:70px;}
</style>
    @if(count($errors))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> 错误!</h4>
            @if(is_object($errors))
                @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
            @else
                <p>{{$errors}}</p>
            @endif
        </div>
    @endif
    <script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.config.js') }} "></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.all.min.js') }}"> </script>
    <div class="box box-info">
        <!-- /.box-header -->
        <!-- form start -->
        @if (isset($data) )
            @section('contentheader_title','编辑商品')
        @section('brade_line')
            @parent
            <li><a href="{{ url('goodslist') }}"><i class="fa "></i>全部商品</a></li>
            <li><a href="{{ url('goods/edit',['id'=>$data['id']]) }}"><i class="fa "></i>编辑商品</a></li>
        @stop
        <form class="form-horizontal" action="{{url('goods/goodssave')}}" method="post"  enctype ="multipart/form-data">
            @else
                @section('contentheader_title','添加商品')
            @section('brade_line')
                @parent
                <li><a href="{{ url('goodslist') }}"><i class="fa "></i>全部商品</a></li>
                <li><a href="{{ url('goods/goodsadd') }}"><i class="fa "></i>添加商品</a></li>
            @stop
            <form class="form-horizontal" action="{{ url('goods/goodscreate') }}" method="post"  enctype ="multipart/form-data">
                @endif
                <div class="box-body">

	                </div>                 
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                        </div>
                    </div>
				<div class="form-group">
					<label class="col-sm-2 control-label" >商品类型</label>
					<div class="col-sm-10">
						<input type="radio" onclick="change_goodsgift(1)" @if(!isset($data) || $data->goods_gift == 1) checked="true" @endif value="1"  name="goods_gift">普通商品
						<input type="radio" onclick="change_goodsgift(2)" @if(isset($data) && $data->goods_gift == 2) checked="true" @endif value="2"  name="goods_gift">积分兑换
					</div>
					<script>
						function change_goodsgift(type) {
							if(type==1){
								$('#use_score').hide();
							}else if(type==2){
								$('#use_score').show();
							}
						}
					</script>

				</div>
				<div class="form-group" id="use_score"  @if(isset($data) && $data->use_score > 0) @else style="display:none" @endif >
					<label class="col-sm-2 control-label" >可用积分</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="use_score" placeholder="Enter ..." value="{{ $data->use_score or 0 }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" >可否退货</label>
					<div class="col-sm-10">
						<input type="radio"  @if(!isset($data) || $data->can_back == 0) checked="true" @endif value="0"  name="can_back">可以退
						<input type="radio"  @if(isset($data) && $data->can_back == 1) checked="true" @endif value="1"  name="can_back">不可退
					</div>
				</div>
                  <input type="hidden" name="supplier_id" id="supplier_id" value="{{$_GET['supplier_id'] or $data->supplier_id}} ">
                  <input type="hidden" name="class_id" id="class_id" value="{{$_GET['class_id'] or $data->class_id}} ">
                   <div class="form-group">
	                  <label class="col-sm-2 control-label" for="inputEmail3">店内商品分类</label>
	                  <div class="col-sm-10">	                  
	                  <select name="store_class"  class="form-control  " style="float:left;width:150px">
                          <option value="">请选择分类</option>                          
                             @foreach ($store_class as $class)
		           	              <option value="{{$class->id}}"   @if(!isset($data) || $data->store_class==$class->id) selected   @endif  >{{$class->name}}</option>
                          	 @endforeach							
                      </select>                                   
	                  </div>
	                </div>  	
  
  
  				
  					@foreach ($spec_data as $spec)
  					<div class="form-group" class="sp_{{$spec->spec_id}}">
                        <label class="col-sm-2 control-label" ><input style="width:70px;" type="text" name="spec_name[{{$spec->spec_id}}]" value="{{$spec->spec_name}}" ></label>
                        <div class="col-sm-10">                            
                             @foreach ($spec->val_list as $val)                            
                            	<input type="checkbox"  onclick="create_box(this)" spec_id="{{$spec->spec_id}}" val_id="{{$val->id}}" js_name="{{$spec->spec_id}}_{{$val->id}}" name="spec_value[{{$spec->spec_id}}][{{$val->id}}]"    value="{{ $val->name or '' }}"> {{$val->name}}                            	
                            @endforeach	                            
                            <button class="btn btn-info " type="button"  id="add_spec_{{$spec->spec_id}}" onclick="add_spec({{$spec->spec_id}})" >添加</button>
                            <span id="add_spec_box_{{$spec->spec_id}}" style="display:none">
	                            <input type="text" name="" id="add_spec_value_{{$spec->spec_id}}">
	                            <button class="btn btn-success " type="button" id="add_spec_btn_{{$spec->spec_id}}" onclick="add_spec_btn({{$spec->spec_id}})">确定</button>
	                            <button class="btn btn-info " type="button" id="add_spec_cancel_{{$spec->spec_id}}" onclick="add_spec_cancel({{$spec->spec_id}})">取消</button>
                            </span>
                        </div>
                    </div>    
  					 @endforeach	         
  					 
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >价格设置</label>
                        <div class="col-sm-10">
                            
           <div class="box-body table-responsive no-padding" id="box_table">
           
              
            </div> 	
                            
                            
                        </div>
                    </div>  
                    
                    
                    
                    

                    					 
  					 
<script>
       
                             
  					 var count_spec={{$count_spec}};


  					 
  					 function add_spec(spec_id){
  						$('#add_spec_'+spec_id).hide();		
  						$('#add_spec_box_'+spec_id).show();	  								
  	  				}

  					 function add_spec_btn(spec_id){
  	  					var supplier_id=$('#supplier_id').val();
  	  				    var spec_value=$('#add_spec_value_'+spec_id).val();


  	  				    if(supplier_id==0){
								alert('请先选择供应商');
								return ;
  	  	  				}

                		var url="{{ url('ajax/addspec') }}";	
                		var str='';
                		$.ajax({
                			url: url,
                			type: 'POST',
                			data:{supplier_id:supplier_id,spec_value:spec_value,spec_id:spec_id},
                			dataType: 'JSON',
                			error: function () {  },
                			success: function(data){
								//关闭对话框，显示新加的规格
								str=' <input type="checkbox" onclick="create_box(this)"  spec_id="'+spec_id+'" val_id="'+data+'" name="spec_value['+spec_id+']['+data+']" value="'+spec_value+'"    > '+spec_value+' ';
								$('#add_spec_'+spec_id).before(str);									
	    						$('#add_spec_'+spec_id).show();		
	    						$('#add_spec_box_'+spec_id).hide();	  	
                			}
                		});								
   	  				}    	

  					 function add_spec_cancel(spec_id){
    						$('#add_spec_'+spec_id).show();		
    						$('#add_spec_box_'+spec_id).hide();	  								
    	  				} 

  					 

  						var vie_mode={{$vie_mode}};
				        var spec_arr=[];
					    var s_list=[];
  						var v_list=[];	
						var str='';
						var table_head='';
						var test=[];
						var table_list='';
						var sp_count=[];
						var pv_list=[];

						if(vie_mode==1){
							
							var value={!! $v_list !!};
							<?php 
								$str='';
								foreach($check_spec as $key=>$val){
									foreach($val as $k=>$v){
										$str.="$(".'"'."input[name='spec_value[$key][$k]']".'"'.").trigger('click');";
									}
								}
								echo $str;
							?>						
						}else{
							var value='';
						}




						
  					function create_box(el){



					    s_list=[];
  						v_list=[];	
  						table_head='';
  						table_list='';
  						pv_list=[];
						
  						$.each($("input[name^='spec_value']:checked"),function(i,val){
  	  						
						 		
  			              var s_id=$(this).attr('spec_id');
  			              var v_id=$(this).attr('val_id');
  			             	


						if(s_id!=" " && $.inArray(s_id, s_list)=='-1') {
							s_list.push(s_id);								
						}


  			            });



			              //检查所选属性是否足够
						if(s_list.length==count_spec){
							
							  //更具所选生成表格
							  
							//生成表头  
							for(var i=0;i<s_list.length;i++){
								  table_head+="<td>"+$("input[name='spec_name["+s_list[i]+"]']").val()+"</<td>";
							}


							//生成表内容  
							for(var i=0;i<s_list.length;i++){								
								var v_id='';
	  	  						$.each($("input[name^='spec_value["+s_list[i]+"']:checked"),function(vi,val){		  	  								  	  						
	  	  						 	v_id=v_id+','+$(this).attr('val_id');
		    			         });
		  	  					v_list[s_list[i]]=v_id.substring(1).split(','); 
							}	
							

							for(var i=0;i<v_list.length;i++){
								if(v_list[i]!=undefined){
									pv_list.push(v_list[i]);									
								}
								
							}
							
							if(count_spec==2){

								var aaa='';
								var bbb='';
								table_list='';
								for(var i=0;i<v_list[s_list[0]].length;i++){									
									for(var m=0;m<v_list[s_list[1]].length;m++){

										if("undefined" ==typeof value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]){
											var i_market_price =0;
											var i_price =0;
											var i_cost_price =0;
											var i_supplier_price =0;
											var i_rebate_amount =0;
											var i_num =0;
	
										}else{
											var i_market_price =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['market_price'];
											var i_price =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['price'];
											var i_cost_price =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['cost_price'];
											var i_supplier_price =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['supplier_price'];
											var i_rebate_amount =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['rebate_amount'];
											var i_num =value[v_list[s_list[0]][i]+v_list[s_list[1]][m]]['num'];
											}
										
										aaa=$("input[name='spec_value["+s_list[0]+"]["+v_list[s_list[0]][i]+"]']:checked").val();										
										bbb =$("input[name='spec_value["+s_list[1]+"]["+v_list[s_list[1]][m]+"]']:checked").val();
								       table_list += "<td>"+aaa+"</td>"+
								             "<td>"+bbb+"</td>"+        //"+value[[v_list[s_list[0]][i]][v_list[s_list[1]][m]]['market_price']]+"
								           	 " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][market_price]]' value="+ i_market_price+" class='val_class'> 元</td>"+
									         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][price]]' value="+ i_price+" class='val_class'> 元</td>"+
									         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][cost_price]]' value="+ i_cost_price+" class='val_class'> 元</td>"+
									         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][supplier_price]]' value="+ i_supplier_price+" class='val_class'> 元</td>"+
									         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][rebate_amount]]' value="+ i_rebate_amount+" class='val_class'> 元</td>"+
									         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+v_list[s_list[1]][m]+"][num]]' value="+ i_num+" class='val_class'></td></tr>";
									}
								}
							}else if(count_spec==1){
								//alert(value[22]['num']);
								for(var i=0;i<v_list[s_list[0]].length;i++){

									if("undefined" ==typeof value[v_list[s_list[0]][i]]){
										var i_market_price =0;
										var i_price =0;
										var i_cost_price =0;
										var i_supplier_price =0;
										var i_rebate_amount =0;
										var i_num =0;

									}else{
										var i_market_price =value[v_list[s_list[0]][i]]['market_price'];
										var i_price =value[v_list[s_list[0]][i]]['price'];
										var i_cost_price =value[v_list[s_list[0]][i]]['cost_price'];
										var i_supplier_price =value[v_list[s_list[0]][i]]['supplier_price'];
										var i_rebate_amount =value[v_list[s_list[0]][i]]['rebate_amount'];
										var i_num =value[v_list[s_list[0]][i]]['num'];
									}
									
									aaa=$("input[name='spec_value["+s_list[0]+"]["+v_list[s_list[0]][i]+"]']:checked").val();

								       table_list += "<td>"+aaa+"</td>"+
							           	 " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][market_price]]' value="+ i_market_price+" class='val_class'></td>"+
								         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][price]]' value="+ i_price+" class='val_class'></td>"+
								         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][cost_price]]' value="+ i_cost_price+" class='val_class'></td>"+
								         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][supplier_price]]' value="+ i_supplier_price+" class='val_class'></td>"+
								         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][rebate_amount]]' value="+ i_rebate_amount+" class='val_class'></td>"+
								         " <td><input type='text' name='spec["+v_list[s_list[0]][i]+"][num]]' value="+ i_num+" class='val_class'></td></tr>";
								}
																
							}

				             str =" <table class='table table-hover'>"+
				                "<tbody>"+
				                	"<tr>";
											                					                	
					         str+=table_head;
					         str+="   <td>原价</td>"+
					              "    <td>销售价</td>"+
					              "    <td>成本价</td>"+
					              "    <td>供应商结算价</td>"+
					              "    <td>返利金额</td>"+
					              "    <td>库存</td>"+
				                  " </tr>"+                
					              " <tr>";
					        //生成所选属性      
					        str+=table_list;				        



						                       				
						     str+="</tbody>"+
				            "</table>";


				            $('#box_table').html(str);

						}else if(s_list.length<count_spec){
							$('#box_table').html(' ');			
						}
  	  				}     				  				
  					 

  					 </script>
  					 
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >运费</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="shipping_price" placeholder="Enter ..." value="{{ $data->shipping_price or 0 }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品排序</label>
                        <div class="col-sm-10">
                            <input type="text"  name="sort"  placeholder="Enter ..." class="form-control" value="{{ $data->sort or 255 }}">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品详情</label>
                        <div class="col-sm-10">
                            <script id="editor" type="text/plain" style="width:1024px;height:500px;">{!! $data->content or '' !!}</script>
                            <input type="hidden" name="content" id="content" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >上架</label>
                        <div class="col-sm-10">
                                <input type="radio"  @if(!isset($data) || $data->state == 1) checked="true" @endif value="1"  name="state">是
                                <input type="radio"  @if(isset($data) && $data->state == 0) checked="true" @endif value="0"  name="state">否
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEmail3">商品图1</label>
                        <div class="col-sm-10">
                            <input type="file" name="image[]" value="" >
                            <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                            <br>
                            @if (isset($images[0]) && $images[0]->image!='')
                                <a target="_blank" href="{{ $images[0]->image }}"><img  width="200" height="100" src="{{ $images[0]->image }}" ></a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEmail3">商品图2</label>
                        <div class="col-sm-10">
                            <input type="file" name="image[]" value="" >
                            <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                            <br>
                            @if (isset($images[1]) && $images[1]->image!='')
                                <a target="_blank" href="{{ $images[1]->image }}"><img  width="200" height="100" src="{{ $images[1]->image }}" ></a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEmail3">商品图3</label>
                        <div class="col-sm-10">
                            <input type="file" name="image[]" value="" >
                            <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                            <br>
                            @if (isset($images[2]) && $images[2]->image!='')
                                <a target="_blank" href="{{ $images[2]->image }}"><img  width="200" height="100" src="{{ $images[2]->image }}" ></a>
                            @endif
                        </div>
                    </div>                                        
<script type="text/javascript">  
                        //实例化编辑器
                        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                        var ue = UE.getEditor('editor');

                        ue.addListener('blur',function(){
                            var con=UE.getEditor('editor').getContent();
                            $('#content').val(con);
                        });

                        function isFocus(e){
                            alert(UE.getEditor('editor').isFocus());
                            UE.dom.domUtils.preventDefault(e)
                        }
                        function setblur(e){
                            UE.getEditor('editor').blur();
                            UE.dom.domUtils.preventDefault(e)
                        }
                        function insertHtml() {
                            var value = prompt('插入html代码', '');
                            UE.getEditor('editor').execCommand('insertHtml',value )
                        }

                    	$('#change_class').change(function(){
                    		var first_id=$('#change_class').val();
                    		var url="{{ url('ajax/getgoodsclass') }}";	
                    		var str='';
                    		$.ajax({
                    			url: url,
                    			type: 'POST',
                    			data:{id:first_id},
                    			dataType: 'JSON',
                    			error: function () {  },
                    			success: function(data){
                    				$.each(data,function(i,val){
                    					 str+="<option value="+val.id+">"+val.name+"</option>";
                    				})
                    				$('#class_list').html(str);
                    			}
                    		});
                    	 });                        
                    </script>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                    {{--<input type="hidden" name="type" value="{{ $type}} ">--}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button class="btn btn-info pull-right" type="submit">提交</button>
                </div>
                <!-- /.box-footer -->
            </form>
    </div>
@endsection
