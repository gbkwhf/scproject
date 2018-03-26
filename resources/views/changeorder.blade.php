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
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
             @if (isset($data) )
             	@section('contentheader_title','修改订单归属人')
             	<form class="form-horizontal" action="{{ url('manage/changeordersave') }}" method="post"  enctype ="multipart/form-data">
             @else             	
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >订单号</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;" >
                  		
                  		<span>	{{ $data->order_id or '' }}</span>
                  </div>
                </div>              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >用户名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;" >
                  		
                  		<span>	{{ $data->name or '' }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >手机号</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;" >
                  		
                  		<span>	{{ $data->mobile or '' }}</span>
                  </div>
                </div>                               
                <div class="form-group">
                  <label class="col-sm-2 control-label" >下单时间</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->create_time or '' }}
                  </div>
                </div>      
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">修改为</label>
                  <div class="col-sm-3">
                   <div class="input-group">
                		<input type="text" class="form-control" id="phone" name="phone" placeholder="输入手机号"  value="">
                    	<span class="input-group-btn">
                      	<button class="btn btn-info btn-flat" id="searchBtn" type="button">查询用户信息</button>
                    	</span>
              		</div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >新用户信息</label>
                  <div class="col-sm-10">
                    <input type="text"  id="tips"  placeholder="" class="form-control" value="">
                  </div>
                </div> 
                                            
                                                                                                                 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->order_id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                 <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
      <script type="text/javascript">
        $(document).ready(function(){	
        	$('#searchBtn').click(function(){
        		var phone=$("#phone").val();
        		var shop_id=$("#shop_id").val();
        		var url="{{ url('ajax/getuserinfotoorder') }}";	
        		var str='';
        		$.ajax({
        			url: url,
        			type: 'POST',
        			data:{phone:phone},//检查是否可以做店长
        			dataType: 'JSON',
        			error: function () {  },
        			success: function(data){
        				str=data.name;
						if(data==3){
							str='手机号未注册';														
						}													
        				$('#tips').val(str);
        			}
        		});
        	 });
        	
        	});	                        
        </script>  
@endsection
