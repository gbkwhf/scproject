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

             	@section('contentheader_title','用户咨询')
             	<form class="form-horizontal" action="{{ url('question/questionsave') }}" method="post"  >
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系人</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系电话</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->mobile or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >咨询时间</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->created_at or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >咨询类型</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->type or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >会员身份</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->grade or '' }}">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-2 control-label" >咨询详情</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" disabled="true" rows="3" placeholder="">{{ $data->content or '' }}</textarea>
                  </div>
                </div>
                @if($data->state == 0)
                <div class="form-group">
                  <label class="col-sm-2 control-label" >状态</label>
                  <div class="col-sm-10">
                      <select class="form-control" name="state">
                      	<option @if (isset($data) &&  $data->state == 0) selected="" @endif value="0">未处理</option>
                      	<option @if (isset($data) &&  $data->state == 1) selected="" @endif value="1" >已处理</option>
                  	  </select>
                  </div>
                </div>
                @elseif($data->state == 1)
                  <div class="form-group">
                    <label class="col-sm-2 control-label" >状态</label>
                    <div class="col-sm-10">
                      <input type="text"  name="state" disabled="true"  placeholder="Enter ..." class="form-control"  value="已处理" >
                    </div>
                  </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-2 control-label" >反馈人</label>
                  <div class="col-sm-10">{{----}}
                    <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" @if($data->state == 0)   value="{{ Auth::user()->name }}"@else value="{{ $data->manage_id or '' }}" @endif>
                  </div>
                </div>
                @if($data->state == 1)
                <div class="form-group">
                  <label class="col-sm-2 control-label" >反馈时间</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone"  disabled="true" placeholder="Enter ..." class="form-control"
                          value="{{ $data->manage_time or '' }}" >
                  </div>
                </div>
                @endif
                <div class="form-group">{{----}}
                  <label class="col-sm-2 control-label" >反馈详情</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" @if($data->state == 1) disabled="true" @endif rows="3" placeholder="" name="content">{{ $data->manage_content or '' }}</textarea>
                  </div>
                </div>                                                                                                                                                                                                          
              </div>
              <!-- /.box-body -->
             @if($data->state == 0)
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
            @endif
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
