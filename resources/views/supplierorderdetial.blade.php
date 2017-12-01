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
             	@section('contentheader_title','发货')
             	<form class="form-horizontal" action="{{ url('supplier/ordersend') }}" method="post"  enctype ="multipart/form-data">
             @else             	
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >所购商品</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->goods_name or '' }}
                  </div>
                </div>              
                <div class="form-group">
                  <label class="col-sm-2 control-label" >收货地址</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->receive_address or '' }}
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" >用户备注</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->user_remark or '' }}
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" >客服备注</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->manage_remark or '' }}
                  </div>
                </div>                                                                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">快递公司</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="express_name" placeholder="Enter ..." value="{{ $data->express_name or '' }}">
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">单号</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="express_num" placeholder="Enter ..." value="{{ $data->express_num or '' }}">
                  </div>
                </div>                                              
                                                                                                                 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->order_id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
