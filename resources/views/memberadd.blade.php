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

            @if (session('message'))              
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                	{{ session('message') }}
              </div>
            @endif

    <div class="box box-info">
        <!-- /.box-header -->
        <!-- form start -->

        @section('contentheader_title','注册会员')
        <form class="form-horizontal" action="{{ url('member/memberaddsave') }}" method="post"  >
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >会员名</label>
                    <div class="col-sm-10">
                        <input type="text"  name="username"    placeholder="Enter ..." class="form-control" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >注册手机号</label>
                    <div class="col-sm-10">
                        <input type="text"  name="mobile"  placeholder="Enter ..." class="form-control" value="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >性别</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="sex">
                            <option value="1" >男</option>
                            <option value="2">女</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >密码</label>
                    <div class="col-sm-10">
                        <input type="text"  name="password"  placeholder="Enter ..." class="form-control" value="">
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
