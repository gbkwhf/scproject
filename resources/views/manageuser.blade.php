@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','管理员列表')



@section('main-content')
<style>
<!--
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
	line-height: 54px;
}
-->
</style>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <a href="{{ url('manage/manageadd') }}"><button type="button" class="btn bg-olive margin">添加</button></a>
              
              <div class="box-tools">

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>账号</th>
                  <th>权限</th>
                  <th>EMAIL</th>
                  <th>创建时间</th>
                  <th>修改时间</th> 
                   <th>操作</th>
                </tr>                
                @foreach ($data as $apply)    				
	    			<tr>
	                  <td>{{ $apply->id }}</td>
	                  <td>{{ $apply->name }}</td>
	                  <td>{{ $apply->role }}</td>
	                  <td>{{ $apply->email }}</td>
	                  <td>{{ $apply->created_at }}</td>
	                  <td>{{ $apply->updated_at }}</td>	                  
	                  <td>
	                  		<a href="{{ url('manage/manageedit',['id'=>$apply->id]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
	                  </td>
	                </tr>                
				@endforeach               
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            	{!! $data->render() !!}
            </div>
          </div>
          <!-- /.box -->          
        </div>        
      </div>

@endsection
