<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
@section('main-content')


    <div class="box box-info">
        <!-- /.box-header -->
        <!-- form start -->

        {{-- @if (isset($data) )--}}
        @section('contentheader_title','详情')
        @section('brade_line')
            @parent
            <li><a href="{{ url('orderlist') }}"><i class="fa "></i>订单列表</a></li>
            {{--<li><a href="{{ url('order/deliver',['id'=>$data['id']]) }}"><i class="fa "></i>发货</a></li>--}}
        @stop

            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >商品名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled="true " name="title" placeholder="Enter ..." value="{{ $data['name']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >购买人</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="user_name"  placeholder="Enter ..." class="form-control" value="{{ $data['by_name']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >购买数量</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="num"  placeholder="Enter ..." class="form-control" value="{{ $data['num']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >收货人</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="receive_name"  placeholder="Enter ..." class="form-control" value="{{ $data['receive_name']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >收货人联系方式</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="receive_phone"  placeholder="Enter ..." class="form-control" value="{{ $data['receive_phone']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >收货人联系地址</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="receive_address"  placeholder="Enter ..." class="form-control" value="{{ $data['receive_address']}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >快递公司</label>
                    <div class="col-sm-10">
                        {{--<input type="text" disabled="true "  name="receive_address"  placeholder="Enter ..." class="form-control" value="{{ $data['receive_address']}}">--}}
                        <input type="text" disabled="true "  name="express"   class="form-control" value="{{ $data['express']}}">
                        {{--<select name="express" id="express" class="form-control">
                            <option value="">选择一家快递公司</option>
                            <option value="1" >顺丰速递</option>
                            <option value="2">韵达快递</option>
                            <option value="3">中通快递</option>
                            <option value="4">申通速递</option>
                            <option value="5">天天快递</option>
                            <option value="6">宅急送</option>
                        </select>--}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >快递单号</label>
                    <div class="col-sm-10">
                        <input type="text" disabled="true "  name="tracking_number"   class="form-control" value="{{$data['tracking_number']}}" >
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
    </div>
@endsection
