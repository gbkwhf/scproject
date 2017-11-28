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
             	@section('contentheader_title','申请提现')
             	<form class="form-horizontal" action="{{ url('supplier/suppliercash') }}" method="post"  enctype ="multipart/form-data">             
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >可提现金额</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->balance or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >申请中提现金额</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->apply_amount or '' }}
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
                  <label class="col-sm-2 control-label" for="inputEmail3">申请提现金额</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="amount" placeholder="Enter ..." value="">
                  </div>
                </div>                                                                                                                                                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
