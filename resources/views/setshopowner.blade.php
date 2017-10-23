<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

@section('main-content')
         
 
            @if (session('message'))              
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                	{{ session('message') }}
              </div>
            @endif
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->

             	@section('contentheader_title','修改店长')
             	<form class="form-horizontal" action="{{ url('shop/setshopownersave') }}" method="post"  >
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >店名</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control"   value="{{ $shop_info->name }}">
                  </div>
                </div>              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >当前店长</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name"  value="{{ $info->user_name or '暂无' }}{{ $info->mobile or '' }}">
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
                  <label class="col-sm-2 control-label" >新店长信息</label>
                  <div class="col-sm-10">
                    <input type="text"  id="tips"  placeholder="" class="form-control" value="">
                  </div>
                </div>                                                                                                                                                                                           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_info->id or '' }} ">
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
        		var url="{{ url('ajax/getuserinfo') }}";	
        		var str='';
        		$.ajax({
        			url: url,
        			type: 'POST',
        			data:{phone:phone,shop_id:shop_id},
        			dataType: 'JSON',
        			error: function () {  },
        			success: function(data){
        				str=data.user_name;
						if(data==3){
							str='手机号未注册';														
						}
						if(data==1){
							str='已是本店店长';
						}
						if(data==2){
							str='已在其他店';
						}						
        				$('#tips').val(str);
        			}
        		});
        	 });
        	
        	});	                        
        </script>          
@endsection
