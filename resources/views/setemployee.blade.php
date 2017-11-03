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

             	@section('contentheader_title','修改员工')
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>UID</th>
                  <th>用户名</th>
                  <th>手机号</th>
                   <th>操作</th>
                </tr>                
                @foreach ($data as $employee)    				
	    			<tr>
	                  <td>{{ $employee->user_id }}</td>
	                  <td>{{ $employee->name }}</td>
	                  <td>{{ $employee->mobile }}</td>	
	                  <td>	                  		
							<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('agency/deleteemployee',['id'=>$employee->id]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>                 		
	                  </td>
	                </tr>                
				@endforeach               
              </tbody></table>
            </div>  
            
            
            
                       	
             	<form class="form-horizontal" action="{{ url('agency/setemployeesave') }}" method="post"  >
              <div class="box-body">
            
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">添加员工</label>
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
                  <label class="col-sm-2 control-label" >新员工信息</label>
                  <div class="col-sm-10">
                    <input type="text"  id="tips"  placeholder="" class="form-control" value="">
                  </div>
                </div>                                                                                                                                                                                           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              	<input type="hidden" name="agency_id" id="agency_id" value="{{ $agency_id }}" />  
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
        		var agency_id=$("#agency_id").val();
        		var url="{{ url('ajax/getuserinfo') }}";	
        		var str='';
        		$.ajax({
        			url: url,
        			type: 'POST',
        			data:{phone:phone,agency_id:agency_id},
        			dataType: 'JSON',
        			error: function () {  },
        			success: function(data){
        				str=data.name;
						if(data==3){
							str='手机号未注册';														
						}
						if(data==2){
							str='已在其他店';
						}	
						if(data==1){
							str='已是本店员工';
						}											
        				$('#tips').val(str);
        			}
        		});
        	 });
        	
        	});	                        
        </script>          
@endsection
