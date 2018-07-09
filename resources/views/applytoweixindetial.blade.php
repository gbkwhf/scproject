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
             	@section('contentheader_title','用户申请提现到微信')
             	<form class="form-horizontal" action="{{ url('manage/applytoweixinsave') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >用户名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->name or '' }}
                  </div>
                </div>    
                <div class="form-group">
                  <label class="col-sm-2 control-label" >手机号</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->mobile or '' }}
                  </div>
                </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label" >申请时间</label>
                      <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                          {{ $data->created_at or '' }}
                      </div>
                  </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >金额</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->amount or '' }}
                  </div>
                </div>
                 <div class="form-group">
                      <label class="col-sm-2 control-label" >确认处理</label>
                      <div class="col-sm-10">
                          <select class="form-control" name="state">
                              <option @if (  $data->state == 0) selected="" @endif value="1" >申请中</option>
                              <option @if (isset($data) &&  $data->state == 1) selected="" @endif value="1" >通过</option>
                              <option @if (isset($data) &&  $data->state == 2) selected="" @endif value="0">拒绝</option>
                          </select>
                      </div>
                 </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="hidden" name="id" value="{{ $data->id }}" />
                 @if($data->state == 0)
                     <button class="btn btn-info pull-right" type="submit">提交</button>
                 @endif
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
