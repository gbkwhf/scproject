<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','门店分类列表')
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
             {{--<div class="box-header">--}}
               {{--<br><br>--}}
                  {{--<div class="box-tools2 ">--}}
                      {{--<form class="form-horizontal" id ="form_action" action="{{url('goodslist')}}" method="get">--}}
                          {{--<div style="width: 800px;" class="input-group input-group-sm row">--}}
                             {{--<div class="col-lg-3">--}}
	                            {{--<select name="supplier"  class="form-control pull-right"  style="width: 200px">--}}
	                                {{--<option value=""> 供应商 </option>--}}
			                          {{--@foreach ($suppliers as $supplier)--}}
			                          {{--<option @if(isset($_GET['supplier'])) @if($_GET['supplier'] == $supplier['id']) selected="selected" @endif @endif value="{{$supplier['id']}}">{{$supplier['name']}}</option>--}}
			                          {{--@endforeach--}}
	                            {{--</select>--}}
                              {{--</div>--}}
                             {{--<div class="col-lg-3">--}}
	                            {{--<select name="state"  class="form-control pull-right"  style="width: 200px">	                                --}}
                                    {{--<option value=-1>商品状态</option>--}}
                                    {{--<option @if(isset($_GET['state']) && $_GET['state'] == 1) selected @endif  value="1">正常</option>--}}
                                    {{--<option @if(isset($_GET['state']) && $_GET['state'] == 0) selected @endif value="0">下架</option>--}}
	                            {{--</select>--}}
                              {{--</div>                              --}}
                              {{--<div class="col-lg-2" style="position:relative">--}}
                                  {{--<input type="hidden" name="search" value="1">--}}
                                  {{--<input type="text" placeholder="名称" class="form-control  " style="float:left;width:141px" name="name" value="{{ $_GET['name'] or ''}}">--}}
                                  {{--<button class="btn btn-default" style="position:absolute;right:-47px;height:34px;" type="submit"><i class="fa fa-search"></i></button>                                  --}}
                              {{--</div>--}}
                          {{--</div>--}}
                      {{--</form>--}}
                  {{--</div>--}}
                   <div style="float:right;margin-top: -55px;">
                          <a href="{{url('supplieredit/shop/class/add',['id'=>$store_id])}}">
                              <button type="button" class="btn bg-olive margin" >添加门店分类</button>
                          </a>
                   </div>

                {{--<a href="{{ url('supplieredit/shop/class/edit',['id'=>$spec->id]) }}">--}}
                    {{--<button class="btn bg-orange margin" type="button">编辑</button>--}}
                {{--</a>--}}

              </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th width="10%">id</th>
                            <th>分类名称</th>
                            <th>门店名称</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        @foreach ($data as $spec)
                            <tr>
                                <td>{{ $spec->id }}</td>
                                <td>{{ $spec->name }}</td>
                                <td>{{ $spec->spec_name }}</td>
                                <td>{{ $spec->sort }}</td>
                                <td>
                                    <a href="{{ url('supplieredit/shop/class/edit',['id'=>$spec->id]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                                    <a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('supplieredit/shop/class/delete',['id'=>$spec->id]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">总数：{{count($num)}}<br>

                    {!! $data->render() !!}
             </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
