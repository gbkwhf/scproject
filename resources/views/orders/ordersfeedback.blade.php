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
    <script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.config.js') }} "></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.all.min.js') }}"> </script>
    <div class="box box-info">
        <!-- /.box-header -->
        <!-- form start -->

        {{-- @if (isset($data) )--}}
        @section('contentheader_title','反馈')
        @section('brade_line')
            @parent
            <li><a href="{{ url('orders/orderslist') }}"><i class="fa "></i>订单列表</a></li>
        @stop

        <form class="form-horizontal" action="{{url('orders/feedback_save')}}" method="post"  enctype ="multipart/form-data">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >订单id</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="order_id" placeholder="Enter ..." value="{{ $data['order_id']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >服务类型</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="type" placeholder="Enter ..." value="{{ $data['service_type']}}-{{$data['service_option']}}">
                    </div>
                </div>
                @if($data['order_type'] == '普通订单')
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >服务套餐</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="type" placeholder="Enter ..." value="{{ $data['option']}}">
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="col-sm-2 control-label" >订单类型</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="order_type" placeholder="Enter ..." value="{{ $data['order_type']}}">
                    </div>
                </div>
                {{--<div class="form-group">
                    <label class="col-sm-2 control-label" >订单状态</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="order_type" placeholder="Enter ..." value="{{ $data['type']}}">
                    </div>
                </div>--}}
                @if($data['order_type'] == '普通订单')
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >订单价格</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="amount" placeholder="Enter ..." value="{{ $data['amount']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >订单备注</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="service_content" placeholder="Enter ..." value="{{ $data['service_content']}}">
                        </div>
                    </div>
                @endif
                @if($data['order_type'] == '精准订单')
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >预约时间</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="intend_time" placeholder="Enter ..." value="{{ $data['intend_time']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >预约医院</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="hospital_id" placeholder="Enter ..." value="{{ $data['hospital_id']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >预约科室</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled="true " name="department_id" placeholder="Enter ..." value="{{ $data['department_id']}}">
                        </div>
                    </div>
                @endif

                {{--<div class="form-group">
                    <label class="col-sm-2 control-label" >反馈人</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"  name="manage_id" placeholder="Enter ..." value="{{ $data['manage_id']}}">
                    </div>
                </div>--}}

                <div class="form-group">
                    <label class="col-sm-2 control-label" >联系人</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="name" placeholder="Enter ..." value="{{ $data['name']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >联系电话</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="mobile" placeholder="Enter ..." value="{{ $data['mobile']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >反馈内容</label>
                    <div class="col-sm-10">
                        <textarea class="form-control"  name="manage_content" placeholder="Enter ..." >{{ $data['manage_content']}}</textarea>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        <div class="box-footer">
            <input type="hidden" name="id" value="{{ $data['order_id']}} ">
            <input type="hidden" name="name" value="{{ Auth::user()->name}} ">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button class="btn btn-info pull-right" type="submit">提交</button>
        </div>
        <!-- /.box-footer -->
        </form>
    </div>
@endsection
