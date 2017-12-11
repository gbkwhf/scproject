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

             	@section('contentheader_title','会员返现')
             	<form class="form-horizontal" action="{{ url('manage/sendmemberbalancesave') }}" method="post"  >
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >每份金额</label>
                  <div class="col-sm-10">
                    <input type="text" id="per_amount" name="balance"  placeholder="Enter ..." class="form-control" value=""><b style="color: red">系统当前符合条件的共{{ $total}}份</b>
                  </div>
                </div>                                                                                                                                                                                          
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />  
				
	
				
				
				              
                <button class="btn btn-info pull-right" onclick="return sub()">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
     <script>

     function sub(){
    	 var total="{{ $total}}";
         var per_amount=$('#per_amount').val();
         var total_amount=per_amount*total;         
    	 if(confirm("总"+total+"份,每份"+per_amount+"元,总金额"+total_amount+"元,确定发放吗？"))
    	 {
    	 }else{
    		 return false;
        }
    }
     
    </script>
@endsection
