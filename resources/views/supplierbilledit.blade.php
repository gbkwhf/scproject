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
             	@section('contentheader_title','提现审批')
             	<form class="form-horizontal" action="{{ url('manage/suppliercashsave') }}" method="post"  enctype ="multipart/form-data">             
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >供应商名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->name or '' }}
                  </div>
                </div>    
                <div class="form-group">
                  <label class="col-sm-2 control-label" >提现金额</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->amount or '' }}
                  </div>
                </div>                              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >开户行</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->bank_name or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >开户行地址</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->bank_address or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >卡号</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->bank_num or '' }}
                  </div>
                </div>     
                <div class="form-group">
                  <label class="col-sm-2 control-label" >持卡人</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->real_name or '' }}
                  </div>
                </div>                                                             
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">状态</label>
                  <div class="col-sm-2">
                     <select name="state" class="form-control">
                     	<option value="-1">请选择</option>
                     	<option value="1">通过</option>
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
