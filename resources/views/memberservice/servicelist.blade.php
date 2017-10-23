@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title',$head_name)

@section('brade_line')
 @parent

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
                @if($type != 3)
                 <a href="{{ url('memberservice/serviceadd',['id'=>$type]) }}"><button type="button" class="btn bg-olive margin">添加</button></a>
                    @endif
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
                  @if($type != 3)
                  <th>形式</th>
                  <th>排序</th>
                    @endif
                  <th>操作</th>
                </tr> 
                           
                @foreach ($data as $class)    	                  		
	    			<tr>
	                  <td>{{ $class['id'] }}</td>
	                  <td>{{ $class['title'] }}</td>
                        @if($type != 3)
                            @if($class['open_type'] ==1)
                                <td>详情</td>
                                @elseif($class['open_type'] == 2)
                                <td>挂号</td>
                                @elseif($class['open_type'] == 3)
                                <td>住院</td>
                                @endif
	                  <td>{{ $class['sort'] }}</td>
                      @endif
	                  <td>
	                  		<a href="{{ url('memberservice/serviceedit',['id'=>$class['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
                          @if($type != 3)
                              @if($class['open_type']!=2 &&  $class['open_type']!=3)
							<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('memberservice/servicedelete',['id'=>$class['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>{{----}}
                              @endif
                                @if($class['open_type']!=2 &&  $class['open_type']!=3)
                              <a href="{{ url('memberservice/optionlist',['id'=>$class['id']]) }}"><button class="btn bg-purple margin" type="button">服务可选项({{$class['option']}})</button></a>
                                @else
                              <a href="{{ url('memberservice/hospitalcalss',['id'=>$class['id']]) }}"><button class="btn bg-olive-active margin" type="button">医院类别 </button></a>
                                @endif
                          @endif
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
