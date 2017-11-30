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
             	@section('contentheader_title','供应商申请加盟')
             	<form class="form-horizontal" action="{{ url('manage/joinsuppliersave') }}" method="post"  enctype ="multipart/form-data">             
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
                  <label class="col-sm-2 control-label" >公司名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->company_name or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >商品名</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->goods_name or '' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >商品描述</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	{{ $data->goods_descript or '' }}
                  </div>
                </div>     
                <div class="form-group">
                  <label class="col-sm-2 control-label" >图片</label>
                  <div class="col-sm-10" style="margin-bottom: 0;padding-top: 7px;">
                  	<a href="{{ $data->img_1 or '' }}" target="_blank">图1</a><br>
                  	<a href="{{ $data->img_2 or '' }}" target="_blank">图2</a><br>
                  	<a href="{{ $data->img_3 or '' }}" target="_blank">图3</a><br>
                  </div>
                </div>                                                             
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">状态</label>
                  <div class="col-sm-2">
                     <select name="state" class="form-control">
                     	<option value="-1">请选择</option>
                     	<option @if(isset($data->state)) @if($data->state == 1) selected="selected" @endif @endif value="1">已处理</option>
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
