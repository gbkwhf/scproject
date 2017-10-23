@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title')
@section('contentheader_title','可选项列表')
@section('brade_line')
    @parent
    <li><a href="{{ url('memberservice',['id'=>$type]) }}"><i class="fa "></i>可选项列表</a></li>
@stop



@section('main-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="{{ url('memberservice/optionadd',['id'=>$id]) }}"><button type="button" class="btn bg-olive margin">添加</button></a>
                </div>
                <div class="box-header">


                    <div class="box-tools">
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>添加时间</th>
                            <th>价格</th>
                            <th>操作</th>
                        </tr>

                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class['id'] }}</td>
                                <td>{{ $class['title'] }}</td>
                                <td>{{ $class['created_at']  }}</td>{{----}}
                                <td>{{ $class['price'] }}</td>
                                <td>
                                    <a href="{{ url('memberservice/optionedit',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                                    <a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('memberservice/optionedelete',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
                                </td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection
