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
             	@section('contentheader_title','售后咨询')
             	<form class="form-horizontal" action="{{ url('manage/answerquestionsave') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >用户名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->name or '' }}
                  </div>
                </div>    
                <div class="form-group">
                  <label class="col-sm-2 control-label" >问题</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->user_problem or '' }}
                  </div>
                </div>                              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >时间</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->created_at or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >回复内容</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	<textarea cols="50" rows="5" name="reply" >{{ $data->merchant_reply or '' }}</textarea>
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
