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
            @section('contentheader_title','选择商品分类')
        @section('brade_line')
            @parent
            
        @stop
        <form class="form-horizontal" action="{{url('goods/goodsadd')}}" method="get"  >

                @endif
                <div class="box-body">
	                <div class="form-group">
	                  <label class="col-sm-2 control-label" for="inputEmail3">商品分类</label>
	                  <div class="col-sm-10">	                  
	                  <select id="change_class"  class="form-control  " style="float:left;width:150px">
                          <option value="">请选择分类</option>                          
                             @foreach ($data as $class)
		           	              <option value="{{$class->id}}"   >{{$class->name}}</option>
                          	 @endforeach							
                      </select>                      
		                  <select name="class_id" id="class_list" class="form-control  " style="float:left;width:150px">
	                          <option value="">请选择分类</option>							
	                      </select>                
	                  </div>
	                </div>   
	                <div class="form-group">
	                  <label class="col-sm-2 control-label" for="inputEmail3">所属供应商</label>
	                  <div class="col-sm-10">	                  
	                  <select name="supplier_id" id="supplier_id" class="form-control  " style="float:left;width:150px">
                          <option value="">请选择供应商</option>
                          @foreach ($suppliers as $supplier)
                          <option  value="{{$supplier['id']}}">{{$supplier['name']}}</option>
                          @endforeach
                      </select>
	                  </div>
	                </div> 	                
                       
<script type="text/javascript">  
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
                 
                    
                    <button class="btn btn-info pull-right" type="submit">提交</button>
                    
                  
                </div>
                <!-- /.box-footer -->
            </form>
    </div>
@endsection
