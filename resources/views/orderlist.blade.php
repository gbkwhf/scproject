<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script src="{{ asset('/laydate/laydate.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','订单列表')



@section('main-content')
    <style>
        .box-header > .box-tools2 {
            position: absolute;
            right: 300px;
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
                      <form class="form-horizontal" id ="form_action" action="{{url('manage/orderlist')}}" method="get">
                          <div style="width: 1000px;" class="input-group input-group-sm row">
                              <div class="col-lg-2">
									<input placeholder="订单号" class="form-control  " style="float:left;width:161px" name="orderid" value="{{ $_GET['orderid'] or ''}}" type="text">                                                            
                              </div> 
                              <div class="col-lg-2">
                                  <input type="text"  placeholder="起始日期" id="start" class="inline laydate-icon form-control" style="float:left;" name="start" value="{{ $_GET['start'] or ''}}">
                              </div>
                              <div class="col-lg-2">
                                  <input type="text" placeholder="结束日期" id="end" class="inline laydate-icon form-control" style="float:left;" name="end" value="{{ $_GET['end'] or ''}}">
                              </div>
                             <div class="col-lg-2">
	                            <select name="agency"  class="form-control pull-right"  >
	                                <option value="">订单来源 </option>
	                                <option value=-1 @if(isset($_GET['agency'])) @if($_GET['agency'] == -1) selected="selected" @endif @endif>线上订单 </option>
			                          @foreach ($agency_list as $agency)
			                          <option @if(isset($_GET['agency'])) @if($_GET['agency'] == $agency->id) selected="selected" @endif @endif value="{{$agency->id}}">{{$agency->name}}</option>
			                          @endforeach
	                            </select>
                              </div>   
                             <div class="col-lg-2">
	                            <select name="supplier"  class="form-control pull-right"  >
	                                <option value="">供应商 </option>
			                          @foreach ($supplier_list as $supplier)
			                          <option @if(isset($_GET['supplier'])) @if($_GET['supplier'] == $supplier->id) selected="selected" @endif @endif value="{{$supplier->id}}">{{$supplier->name}}</option>
			                          @endforeach
	                            </select>
                              </div>    
                              <div class="col-lg-1">
									<input placeholder="用户名" class="form-control  " style="float:left;width:161px" name="name" value="{{ $_GET['name'] or ''}}" type="text">                                                            
                              </div>                                                                                                                                                 
                              <div class="col-lg-1" style="position:relative">
                                  <input type="hidden" name="search" value="1">
                                  <input type="text" placeholder="用户手机" class="form-control  " style="float:left;width:141px" name="mobile" value="{{ $_GET['mobile'] or ''}}">
                                  <button class="btn btn-default" style="position:absolute;right:-120px;height:34px;" type="submit"><i class="fa fa-search"></i></button>
                              </div>
                              <div style="position:absolute;right:-200px;margin-top:-12px;"> <button type="button" class="btn bg-olive margin" onclick="getOrderExcel()">导出</button></div>                                                                                                                                                                  
                          </div>
                          
                      </form>
                  </div>
              </div>
            <!-- /.box-header商品名，金额，付款时间，供应商，订单来源，用户手机，总利润， -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                	<th>订单号</th>
                  <th>商品名</th>
                  <th>金额</th>
                  <th>付款时间</th>
				  <th>供应商</th>                  
                  <th>订单来源</th>
                  <th>用户名</th>                  
                  <th>用户手机</th>
                  <th>订单利润</th>
                   <th>操作</th>
                </tr>                
                @foreach ($data as $order)    				
	    			<tr>
	    			<td>{{ $order->order_id }}</td>
					  <td>{{ $order->goods_name }}</td>
	                  <td>{{ $order->amount }}</td>
	                  <td>{{ $order->pay_time }}</td>
	                  <td>{{ $order->supplier_name }}</td>
	                  <td>{{ $order->order_source }}</td>
	                  <td>{{ $order->user_name }}</td>
	                  <td>{{ $order->mobile }}</td>
	                  <td>{{ $order->all_profit }}</td>	 	                  
	                  <td>
	                  		<a href="{{ url('manage/orderdetial',['id'=>$order->order_id]) }}"><button class="btn bg-orange margin" type="button">订单详情</button></a>
	                  </td>
	                  <td>
	                  		<a href="{{ url('manage/changeorder',['id'=>$order->order_id]) }}"><button class="btn bg-orange margin" type="button">更改</button></a>		
	                  </td>
	                  <td>
	                  		<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('manage/deleteorder',['id'=>$order->order_id]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>		
	                  </td>	                  
	                </tr>                
				@endforeach               
              </tbody></table>
            </div>
            <!-- /.box-body -->
            
            <div class="box-footer clearfix">总数：{{$data->total()}}条 ,总金额：{{$total_amount or 0}}元<br>
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
        	$("#form_action").attr('action',"{{ url('manage/getorderexcel') }}");
        	$("#form_action").attr('method','post');	
        	$("#form_action").submit();
        	$("#form_action").attr('action',"{{ url('manage/orderlist') }}");
        	$("#form_action").attr('method','get');	        	        	
        }  
    </script>
@endsection
