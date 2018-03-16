<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script src="{{ asset('/laydate/laydate.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','会员列表')



@section('main-content')
    <style>
        .box-header > .box-tools2 {
            position: absolute;
            right: 500px;
            top: 5px;
        }
        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12{
        	padding-left:0;
        	padding-right:0;
        }
<!--
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	line-height: 54px;
}
-->        
    </style>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
          
                        <div class="box-header">
               <br><br>
                  <div class="box-tools2 ">
                      <form class="form-horizontal" id ="form_action" action="{{url('memberlist')}}" method="get">
                          <div style="width: 800px;" class="input-group input-group-sm row">
                             <div class="col-lg-2">
	                            <select name="agency"  class="form-control pull-right"  style="width: 150px">
	                                <option value="">经销商</option>
			                          @foreach ($agency_list as $agency)
			                          <option @if(isset($_GET['agency'])) @if($_GET['agency'] == $agency->id) selected="selected" @endif @endif value="{{$agency->id}}">{{$agency->name}}</option>
			                          @endforeach
	                            </select>
                              </div>                            
                              <div class="col-lg-2">
                                  <input type="text"  placeholder="起始日期" id="start" class="inline laydate-icon form-control" style="float:left;" name="start" value="{{ $_GET['start'] or ''}}">
                              </div>
                              <div class="col-lg-2">
                                  <input type="text" placeholder="结束日期" id="end" class="inline laydate-icon form-control" style="float:left;" name="end" value="{{ $_GET['end'] or ''}}">
                              </div>
                              <div class="col-lg-2">
									<input placeholder="手机号" class="form-control  " style="float:left;width:161px" name="mobile" value="{{ $_GET['mobile'] or ''}}" type="text">                                                            
                              </div>                                                            
                              <div class="col-lg-2" style="position:relative">
                                  <input type="hidden" name="search" value="1">
                                  <input type="text" placeholder="会员名" class="form-control  " style="float:left;width:141px" name="name" value="{{ $_GET['name'] or ''}}">
                                  <button class="btn btn-default" style="position:absolute;right:-47px;height:34px;" type="submit"><i class="fa fa-search"></i></button>                                  
                              </div>
                              <div style="position:absolute;right:-120px;margin-top:-12px;"> <button type="button" class="btn bg-olive margin" onclick="getOrderExcel()">导出</button></div>                                                                                                                                                                  
                              
                          </div>
                      </form>
                  </div>
              </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>会员名</th>
                  <th>联系电话</th>
                  <th>性别</th>
                  <th>注册时间</th>
                  <th>邀请人</th>
                  <th>领取礼品</th>
                  <th>状态</th>
                   <th>操作</th>
                </tr>                
                @foreach ($data as $member)    				
	    			<tr>
	                  <td>{{ $member->name }}</td>
	                  <td>{{ $member->mobile }}</td>
	                  <td>{{ $member->sex }}</td>	                  
	                  <td>{{ $member->created_at }}</td>
	                  <td>{{ $member->invite_id }}</td>
	                  <td>{{ $member->gift }}</td>
	                  <td>{{ $member->state }}</td>	
	                  <td>
	                  		<a href="{{ url('memberedit',['id'=>$member->user_id]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
	                  </td>
	                </tr>                
				@endforeach               
              </tbody></table>
            </div>
            <!-- /.box-body -->
            
            <div class="box-footer clearfix">会员：{{$data->total()}}人<br>
            	{!! $data->appends($search)->render() !!}
            </div>
          </div>
          <!-- /.box -->          
        </div>        
      </div>
    <script>
        laydate({
            elem: '#start', //目标元素。由于laydate.js封装了一个轻量级的选择器引擎，因此elem还允许你传入class、tag但必须按照这种方式 '#id .class'
            event: 'focus' //响应事件。如果没有传入event，则按照默认的click
        });
        laydate({
            elem: '#end', //目标元素。由于laydate.js封装了一个轻量级的选择器引擎，因此elem还允许你传入class、tag但必须按照这种方式 '#id .class'
            event: 'focus' //响应事件。如果没有传入event，则按照默认的click
        });

        function getOrderExcel(){
        	$("#form_action").attr('action',"{{ url('memberlistexcel') }}");
        	$("#form_action").attr('method','post');	
        	$("#form_action").submit();
        	$("#form_action").attr('action',"{{ url('memberlist') }}");
        	$("#form_action").attr('method','get');	        	        	
        } 
    </script>
@endsection
