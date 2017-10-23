<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','健康资讯列表')
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
                    <a href="{{url('news/newadd')}}"><button type="button" class="btn bg-olive margin">添加</button></a>
                    <div class="box-tools ">
                        <form class="form-horizontal" action="{{url('newslist')}}" method="get">
                            <div style="width: 600px;" class="input-group input-group-sm row">
                                <div class="col-lg-6">
                                    <input type="hidden" name="search" value="1">
                                    <input type="text" placeholder="资讯标题" class="form-control  " style="float:left;width:220px" name="name" value="{{ $_GET['name'] or ''}}">
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
                            <th width="20%">标题</th>
                            <th>排序</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class['id'] }}</td>
                                <td>{{ str_limit($class['title'],25) }}</td>
                                <td>{{ $class['sort'] }}</td>
                                <td>{{ $class['created_at'] }}</td>

                                <td>
                                    <a href="{{ url('news/edit',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                                    <a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('news/newdel',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
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
