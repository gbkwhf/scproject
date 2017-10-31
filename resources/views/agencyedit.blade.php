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
             @if (isset($data) )
             	@section('contentheader_title','编辑经销商')
             	<form class="form-horizontal" action="{{ url('agencysave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加经销商')
            	<form class="form-horizontal" action="{{ url('agencycreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >手机号</label>
                  <div class="col-sm-10">
                    <input type="text"  name="mobile"  placeholder="Enter ..." class="form-control" value="{{ $data->mobile or '' }}">
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">密码</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="password" placeholder="Enter ..." value="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">收款账户</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="account" placeholder="Enter ..." value="{{ $data->account or '' }}">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">状态</label>
                  <div class="col-sm-10">
                      <select name="state"  class="form-control  " style="float:left;width:100px">
                          <option @if(!isset($data) || $data->state==1) selected   @endif value=1>正常</option>
                          <option @if(isset($data) && $data->state==0) selected   @endif value=0>禁用</option>
                      </select>
                  </div>
                </div>                
                                                                                                                 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
