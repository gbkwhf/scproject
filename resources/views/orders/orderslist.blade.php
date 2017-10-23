<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','需求订单')
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
                    <br><br>
                    <div class="box-tools2 ">
                        <form class="form-horizontal" action="{{url('orders/orderslist')}}" method="get">
                            <div style="width: 800px;" class="input-group input-group-sm row">
                                <div class="col-lg-2">
                                    <select name="grade"  class="form-control  " style="float:left;">
                                        <option value="">会员等级</option>
                                        <option value="1">普通会员</option>
                                        <option value="2">红卡会员</option>
                                        <option value="3">金卡会员</option>
                                        <option value="4">白金卡会员</option>
                                        <option value="5">黑卡会员</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select name="state_type"  class="form-control  " style="float:left;">
                                        <option value="">订单状态</option>
                                        <option value="0">未付款</option>
                                        <option value="1">未反馈</option>
                                        <option value="2">已反馈</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select name="type_order"  class="form-control  " style="float:left;">
                                        <option value="">订单类型</option>
                                        <option value="0">普通订单</option>
                                        <option value="1">精准订单</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" placeholder="订单id" class="form-control  " style="float:left;" name="id" value="{{ $_GET['id'] or ''}}">
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" placeholder="联系人" class="form-control  " style="float:left;width:161px" name="name" value="{{ $_GET['name'] or ''}}">
                                    <button class="btn btn-default" style="float:right;height: 34px;" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>订单ID</th>
                            <th>用户等级</th>
                            <th>服务类型</th>
                            <th>订单类型</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class['order_id'] }}</td>
                                <td>{{ $class['grade'] }}</td>
                                <td>[{{ $class['service_type'] }}]-[{{ $class['service_option']}}]</td>
                                <td>{{ $class['order_type']}}</td>
                                <td>{{ $class['name'] }}</td>
                                <td>{{ $class['mobile'] }}</td>
                                <td>{{ $class['created_at'] }}</td>

                                <td>
                                    @if($class['type'] == 0 )
                                        <a href="{{ url('orders/feedback',['id'=>$class['order_id']]) }}"><button class="btn bg-orange margin" type="button">反&nbsp&nbsp&nbsp&nbsp馈</button></a>
                                    @elseif($class['type'] == 1)
                                        <button class="btn bg-olive-active margin" type="button">已反馈</button>
                                    @elseif($class['type'] == 2 )
                                        <button class="btn bg-gray-active margin" type="button">未付款</button>
                                    @endif
                                    <a href="{{ url('orders/ordersinfo',['id'=>$class['order_id']]) }}"><button class="btn bg-purple margin" type="button">详情</button></a>
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
@endsection
