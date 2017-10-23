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
             	@section('contentheader_title','编辑管理员')
             	<form class="form-horizontal" action="{{ url('manage/managesave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加管理员')
            	<form class="form-horizontal" action="{{ url('manage/managecreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >账号</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >邮箱</label>
                  <div class="col-sm-10">
                    <input type="text"  name="email"  placeholder="Enter ..." class="form-control" value="{{ $data->email or '' }}">
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">密码</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="password" placeholder="Enter ..." value="">
                  </div>
                </div>
                  <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">权限</label>
                  <div class="col-sm-10">
                      <select name="role"  class="form-control  " style="float:left;width:150px">
                          <option value="">请选择权限</option>
                          @foreach ($adminrole as $apply)
                          <option @if(isset($data)) @if($data->role == $apply['id']) selected="selected" @endif @endif value="{{$apply['id']}}">{{$apply['role_name']}}</option>
                          @endforeach
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
