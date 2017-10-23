@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title')
@section('contentheader_title','医院类别')
@section('brade_line')
    @parent
    <li><a href="{{ url('memberservice',['id'=>$id]) }}"><i class="fa "></i>医院类别</a></li>
@stop



@section('main-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

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

                        </tr>

                        @foreach ($data as $class)
                            <tr>
                                <td>{{ $class['id'] }}</td>
                                <td>{{ $class['name'] }}</td>
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
