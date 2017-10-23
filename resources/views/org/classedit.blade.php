<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    
@section('brade_line')
 @parent
<li><a href="{{ url('orgclasslist') }}"><i class="fa "></i>医疗机构</a></li>
<li><a href="{{ url('orgsecondclasslist',['id'=>$type]) }}"><i class="fa "></i>医疗机构二级分类</a></li>           
@stop
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
             @if (isset($data) )
             	@section('contentheader_title','编辑机构分类')
             	<form class="form-horizontal" action="{{ url('orgsecondclass/classsave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加机构分类')
            	<form class="form-horizontal" action="{{ url('orgsecondclass/classcreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >机构分类名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >排序</label>
                  <div class="col-sm-10">
                    <input type="text"  name="sort"  placeholder="Enter ..." class="form-control" value="{{ $data->sort or '' }}">
                  </div>
                </div> 
                                                                                                                    
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                <input type="hidden" name="type" value="{{ $type}} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
