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
            right: 500px;
            top: 5px;
        }
        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12{
        	padding-left:0;
        	padding-right:0;
        }
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	line-height: 54px;
}        
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools2 " >
                        <form class="form-horizontal" name="search" action="{{url('orderlist')}}" method="get">
                            <div style="width: 800px;" class="input-group input-group-sm row" style="position: relative;">
                                <div class="col-lg-2">
                                    <select name="state"  class="form-control  " style="float:left;">
                                        <option value="">订单状态</option>
                                        <option value="0" >未付款</option>
                                        <option value="1">待发货</option>
                                        <option value="2">已发货</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text"  placeholder="起始日期" id="start" class="inline laydate-icon form-control" style="float:left;" name="start" value="{{ $_GET['model'] or ''}}">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" placeholder="结束日期" id="end" class="inline laydate-icon form-control" style="float:left;" name="end" value="{{ $_GET['model'] or ''}}">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" placeholder="收货人电话" class="inline form-control  " style="float:left;" name="model" value="{{ $_GET['model'] or ''}}">
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="search" value="1">
                                    <input type="text" placeholder="商品名称" class="inline form-control  " style="float:left;width:227px" name="name" value="{{ $_GET['name'] or ''}}">
                                    <button class="btn btn-default" style="float:right;height: 34px;" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                
			                     <div style="position: absolute;right: -93px;top: -10px;">
			                        <a href="{{url('order/excel')}}"><button type="button" class="btn bg-olive margin">导出</button></a>
			                        <input type="hidden" name="excel" value="1">
			                    </div>
                            </div>

                        </form>
                        
                    </div>
                    <br>
                  	<br>
                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th width="13%">商品</th>
                            <th>购买数量</th>
                            <th width="">收货人</th>
                            <th>状态</th>
                            <th>快递公司</th>
                            <th>快递单号</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class['id'] }}</td>
                                <td>{{str_limit($class['name'],12)}}</td>
                                <td>{{ $class['num'] }}</td>
                                <td>{{ $class['receive_name'] }}</td>
                                @if($class['state']==0)
                                    <td>未付款</td><td>无</td><td>无</td>
                                @elseif($class['state']==1)
                                    <td>已付款</td><td>待发货</td><td>待发货</td>
                                @else
                                    <td>已处理</td><td>
                                        @if($class['express']==1)顺丰速递
                                        @elseif($class['express']==2)韵达快递
                                        @elseif($class['express']==3)中通快递
                                        @elseif($class['express']==4)申通速递
                                        @elseif($class['express']==5)天天快递
                                        @elseif($class['express']==6)宅急送
                                        @endif </td><td>{{ $class['tracking_number'] }}</td>
                                @endif
                                {{--<td>{{ $class['express'] }}</td>
                                <td>{{ $class['tracking_number'] }}</td>--}}
                                <td>{{ $class['created_at'] }}</td>
                                <td>
                                    @if($class['state']==1)
                                        <a href="{{ url('order/deliver',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">待发货</button></a>
                                    @elseif($class['state']==0)
                                        <button class="btn bg-gray-active margin" type="button">未付款</button>
                                    @else
                                        <button class="btn bg-navy-active margin" type="button">已发货</button>
                                    @endif
                                    {{--<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('order/goodsdel',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>--}}
                                        <a href="{{ url('order/orderinfo',['id'=>$class['id']]) }}"><button class="btn bg-purple margin" type="button">详情</button></a>
                                </td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {!! $data->render() !!}
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
    </script>
@endsection
