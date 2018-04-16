<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script src="{{ asset('/laydate/laydate.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','商品分类列表')



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


<div class="row">
        <div class="col-xs-12">
          <div class="box">
          
                        <div class="box-header">
               <br><br>
                  <div class="box-tools2 ">
                      <form class="form-horizontal" id ="form_action" action="{{url('commody/class/manage/list')}}" method="get">
                          <div style="width: 800px;" class="input-group input-group-sm row">

                             <div class="col-lg-2">
	                            <select name="first_id"  class="form-control pull-right"  >

                                    @foreach($first_info  as  $tmp)

                                        <option  @if(isset($_GET['first_id']) && $_GET['first_id'] == $tmp->id) selected  @endif   value="{{$tmp->id}}">{{$tmp->name}}</option>
                                    @endforeach

	                            </select>
                                 <button class="btn btn-default" style="position:absolute;right:-47px;height:34px;" type="submit"><i class="fa fa-search"></i></button>
                              </div>

                          </div>
                      </form>
                  </div>
                            <div style="float:right;padding-right:150px;margin-top: -55px;"><a href="{{url('commody/class/manage/first/list')}}">  <button type="button" class="btn bg-olive margin" >一级商品分类管理</button></a></div>
                            <div style="float:right;margin-top: -55px;"><a href="{{url('commody/class/manage/second/add')}}">  <button type="button" class="btn bg-olive margin" >添加二级商品分类</button></a></div>
              </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>分类ID</th>
                  <th>分类名称</th>
                  <th>排序</th>
                  <th>所属类型</th>
                  <th>操作</th>
                </tr>                
                @foreach ($data as $class)
	    			<tr>
	                  <td>{{ $class->id }}</td>
	                  <td>{{ $class->name }}</td>
	                  <td>{{ $class->sort }}</td>
	                  <td>{{ $class->type_name }}</td>
                      <td>
                            <a href="{{ url('commody/class/manage/edit',['id'=>$class->id]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                            <a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('commody/class/manage/delete',['id'=>$class->id]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
                       </td>
	                </tr>                
				@endforeach               
              </tbody></table>
            </div>
            <!-- /.box-body -->
            
            {{--<div class="box-footer clearfix">总数：{{$data->total()}},总金额：{{$total or 0}}元<br><br>--}}
            	{{--{!! $data->appends($search)->render() !!}--}}
              <div class="box-footer clearfix">总数：{{count($num)}}<br>

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
    </script>
@endsection
