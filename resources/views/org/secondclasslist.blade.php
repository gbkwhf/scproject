@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','医疗机构二级分类管理')

@section('brade_line')
 @parent
<li><a href="{{ url('orgclasslist') }}"><i class="fa "></i>医疗机构</a></li>
<li><a href="{{ url('orgsecondclasslist',['id'=>$cid]) }}"><i class="fa "></i>医疗机构二级分类</a></li>           
@stop

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
                 <a href="{{ url('orgsecondclass/classadd',['id'=>$cid]) }}"><button type="button" class="btn bg-olive margin">添加</button></a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>分类名称</th>
                  <th>排序</th>
                  <th>操作</th>
                </tr> 
                           
                @foreach ($data as $class)    	                  		
	    			<tr>
	                  <td>{{ $class['id'] }}</td>
	                  <td>{{ $class['name'] }}</td>
	                  <td>{{ $class['sort'] }}</td>
	                  <td>
	                  		<a href="{{ url('orgsecondclass/classedit',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
							<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('orgsecondclass/classdelete',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
							<a href="{{ url('orglist',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">医院列表</button></a>							                 		
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
