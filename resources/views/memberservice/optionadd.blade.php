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
        @if (isset($data) )
            @section('contentheader_title','编辑可选项')
        @section('brade_line')
            @parent
            <li><a href="{{ url('memberservice/optionlist',['id'=>$added_id]) }}"><i class="fa "></i>可选项列表</a></li>
            <li><a href="{{ url('memberservice/optionedit',['id'=>$id]) }}"><i class="fa "></i>编辑可选项</a></li>
        @stop
        <form class="form-horizontal" action="{{url('memberservice/optionesave')}}" method="post"  enctype ="multipart/form-data">
            @else
                @section('contentheader_title','添加可选项')
            @section('brade_line')
                @parent
                <li><a href="{{ url('memberservice/optionlist',['id'=>$id]) }}"><i class="fa "></i>可选项列表</a></li>
                <li><a href="{{ url('memberservice/optionadd',['id'=>$id]) }}"><i class="fa "></i>添加可选项</a></li>
            @stop
            <form class="form-horizontal" action="{{ url('memberservice/optioncreate') }}" method="post"  enctype ="multipart/form-data">
                @endif
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >套餐名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" placeholder="Enter ..." value="{{ $data->title or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >普通会员价格</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price"  placeholder="Enter ..." class="form-control" value="{{ $data->price or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >红卡会员价格</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price_grade1"  placeholder="Enter ..." class="form-control" value="{{ $data->price_grade1 or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >金卡会员价格</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price_grade2"  placeholder="Enter ..." class="form-control" value="{{ $data->price_grade2 or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >白金卡会员价格</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price_grade3"  placeholder="Enter ..." class="form-control" value="{{ $data->price_grade3 or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >黑卡会员价格</label>
                        <div class="col-sm-10">
                            <input type="text"  name="price_grade4"  placeholder="Enter ..." class="form-control" value="{{ $data->price_grade4 or '' }}">

                            <input type="hidden" name="added_id" value="{{$id}} ">

                        </div>{{----}}
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                    {{--<input type="hidden" name="type" value="{{ $type}} ">--}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button class="btn btn-info pull-right" type="submit">提交</button>
                </div>
                <!-- /.box-footer -->
            </form>
    </div>
@endsection
