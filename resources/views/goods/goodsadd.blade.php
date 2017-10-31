<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
@section('main-content')

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
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品库存</label>
                        <div class="col-sm-10">
                            <input type="text"  name="num"  placeholder="Enter ..." class="form-control" value="{{ $data->num or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >销售价</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price"  placeholder="Enter ..." class="form-control" value="{{ $data->price or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >成本价</label>
                        <div class="col-sm-10">
                            <input type="text"  name="cost_price"  placeholder="Enter ..." class="form-control" value="{{ $data->cost_price or '' }}">
                        </div>
                    </div>
	                <div class="form-group">
	                  <label class="col-sm-2 control-label" for="inputEmail3">所属供应商</label>
	                  <div class="col-sm-10">	                  
	                  <select name="supplier_id"  class="form-control  " style="float:left;width:150px">
                          <option value="">请选择供应商</option>
                          @foreach ($suppliers as $supplier)
                          <option @if(isset($data)) @if($data->supplier_id == $supplier['id']) selected="selected" @endif @endif value="{{$supplier['id']}}">{{$supplier['name']}}</option>
                          @endforeach
                      </select>
	                  </div>
	                </div> 
	                <div class="form-group">
	                  <label class="col-sm-2 control-label" for="inputEmail3">商品分类</label>
	                  <div class="col-sm-10">	                  
	                  <select id="change_class"  class="form-control  " style="float:left;width:150px">
                          <option value="">请选择分类</option>
                          <option value="1" @if(isset($data)) @if($data->first_id == 1) selected="selected" @endif @endif >食品土特产</option>
                          <option value="2" @if(isset($data)) @if($data->first_id == 2) selected="selected" @endif @endif >家居家装</option>
                          <option value="3" @if(isset($data)) @if($data->first_id == 3) selected="selected" @endif @endif >健康商城</option>
                          <option value="4" @if(isset($data)) @if($data->first_id == 4) selected="selected" @endif @endif >非返利专区</option>							
                      </select>                      
	                 @if(isset($data)) 
	                     <select name="class_id" id="class_list" class="form-control  " style="float:left;width:150px">
	                            @foreach ($goods_class as $class)
           	               			<option  @if($data->class_id == $class->id) selected @endif   value="{{$class->id}}" > {{$class->name}}</option>
                          		@endforeach							
	                      </select>   	                 
	                 @else	                 
		                  <select name="class_id" id="class_list" class="form-control  " style="float:left;width:150px">
	                          <option value="">请选择分类</option>							
	                      </select>   	                
                      @endif               
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
