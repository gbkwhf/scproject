<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

@section('main-content')
         
 
            @if (session('message'))                 
                <div class="tools-alert tools-alert-green">
                    {{ session('message') }}
                </div>
            @endif
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->

             	@section('contentheader_title','会员详情')
             	<form class="form-horizontal" action="{{ url('membersave') }}" method="post"  >
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >会员名</label>
                  <div class="col-sm-10">
                    <input type="text"  disabled="true "   placeholder="Enter ..." class="form-control" value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >注册手机号</label>
                  <div class="col-sm-10">
                    <input type="text"  disabled="true " placeholder="Enter ..." class="form-control" value="{{ $data->mobile or '' }}">
                  </div>
                </div>                
                
                <div class="form-group">
                  <label class="col-sm-2 control-label" >性别</label>
                  <div class="col-sm-10">
                    <input type="text" disabled="true "  placeholder="Enter ..." class="form-control" value="{{ $data->sex or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系地址</label>
                  <div class="col-sm-10">
                    <input type="text" disabled="true "  name="address"  placeholder="Enter ..." class="form-control" value="{{ $data->address or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >注册时间</label>
                  <div class="col-sm-10">
                    <input type="text"  disabled="true "   placeholder="Enter ..." class="form-control" value="{{ $data->created_at or '' }}">
                  </div>
                </div>                                                  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >状态</label>
                  <div class="col-sm-10">
                      <select class="form-control" name="state">
                      	<option @if (isset($data) &&  $data->state == 1) selected="" @endif value="1" >正常</option>
                      	<option @if (isset($data) &&  $data->state == 0) selected="" @endif value="0">禁止登陆</option>                      	                                          
                  	  </select>
                  </div>
                </div>                                                                                                                                                                                           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="user_id" value="{{ $data->user_id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
