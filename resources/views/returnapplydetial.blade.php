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
             	@section('contentheader_title','用户申请退出')
             	<form class="form-horizontal" action="{{ url('manage/returnapplysave') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系人</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->name or '' }}
                  </div>
                </div>    
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系电话</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->mobile or '' }}
                  </div>
                </div>                              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >时间</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->created_at or '' }}
                  </div>
                </div>
                 <div class="form-group">
                      <label class="col-sm-2 control-label" >确认处理</label>
                      <div class="col-sm-10">
                          <select class="form-control" name="confirm_state">
                              <option @if (isset($data) &&  $data->confirm_state == 1) selected="" @endif value="1" >禁用账号</option>
                              <option @if (isset($data) &&  $data->confirm_state == 0) selected="" @endif value="0">待处理</option>
                          </select>
                      </div>
                 </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="hidden" name="id" value="{{ $data->id }}" />
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
