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

        @section('contentheader_title','会员申请')
        <form class="form-horizontal" action="{{ url('memberapply/save') }}" method="post"  >
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >申请人</label>
                    <div class="col-sm-10">
                        <input type="text"  name="name" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->username or '' }}/{{ $data->membermobile or '' }}">
                    </div>
                </div>
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
                    <label class="col-sm-2 control-label" >申请时间</label>
                    <div class="col-sm-10">
                        <input type="text"  name="phone" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->created_at or '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >申请会员类型</label>
                    <div class="col-sm-10">
                        <input type="text"  name="grade" disabled="true"  placeholder="Enter ..." class="form-control" value="{{ $data->grade or '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >状态</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="state">
                            <option @if (isset($data) &&  $data->state == 0) selected="" @endif value="0">未处理</option>
                            <option @if (isset($data) &&  $data->state == 1) selected="" @endif value="1" >已处理</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button class="btn btn-info pull-right" type="submit">提交</button>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
@endsection
