<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','商品列表')
@section('main-content')
<style>
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
                    <a href="{{url('goods/goodsadd')}}"><button type="button" class="btn bg-olive margin">添加</button></a>
                    <div class="box-tools ">
                        <form class="form-horizontal" action="{{url('goodslist')}}" method="get">
                            <div style="width: 600px;" class="input-group input-group-sm row">
                                <div class="col-lg-3">
                                    <select name="state"  class="form-control  " style="float:left;width:150px">
                                        <option value="">订单类型</option>
                                        <option value="1">上架</option>
                                        <option value="0">下架</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <input type="hidden" name="search" value="1">
                                    <input type="text" placeholder="商品名称" class="form-control  " style="float:left;width:220px" name="name" value="{{ $_GET['name'] or ''}}">
                                    <button class="btn btn-default" style="float:right" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th width="10%">名称</th>
                            <th>排序</th>
                            <th>库存</th>
                            <th>普通会员价</th>
                            <th>红卡会员价</th>
                            <th>金卡会员价</th>
                            <th>白金卡会员价</th>
                            <th>黑卡会员价</th>
                            <th>是否上架</th>
                            <th>操作</th>
                        </tr>
                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>{{ str_limit($class['name'],10)  }}</td>
                                <td>{{ $class['sort'] }}</td>
                                <td>{{ $class['num'] }}</td>
                                <td>{{ $class['price'] }}</td>
                                <td>{{ $class['price_grade1'] }}</td>
                                <td>{{ $class['price_grade2'] }}</td>
                                <td>{{ $class['price_grade3'] }}</td>
                                <td>{{ $class['price_grade4'] }}</td>
                                @if($class['state'] == 1)<td>上架</td>
                                @else<td>下架</td>
                                @endif

                                <td>
                                    <a href="{{ url('goods/edit',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                                    <a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('goods/goodsdel',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
                                </td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {!! $data->appends($search)->render() !!}
             </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
