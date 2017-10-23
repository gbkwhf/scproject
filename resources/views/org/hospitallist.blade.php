<script src="{{ asset('/js/jquery.min.js') }}"></script>

@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','医疗机构列表')

@section('brade_line')
 @parent
<li><a href="{{ url('orgclasslist') }}"><i class="fa "></i>医疗机构</a></li>
<li><a href="{{ url('orgsecondclasslist',['id'=>$cid]) }}"><i class="fa "></i>医疗机构二级分类</a></li>
<li><a href="{{ url('orglist',['id'=>$cid]) }}"><i class="fa "></i>医疗机构列表</a></li>            
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
                 <a href="{{ url('org/orgadd',['id'=>$cid]) }}"><button type="button" class="btn bg-olive margin">添加</button></a>
              <div class="box-tools ">
           	<form class="form-horizontal" action="{{ url('orglist',['id'=>$cid]) }}" method="get">              
                <div style="width: 600px;" class="input-group input-group-sm row">                   
                <div class="col-lg-3">              
                      <select class="form-control" id="province" name="province">
                      	<option value="0">请选择省</option>
                       @foreach ($province as $p)  
                        	<option @if (isset($_GET['province']) && $_GET['province'] == $p->id) selected="" @endif value="{{ $p->id }}">{{ $p->name }}</option>
                       @endforeach  
                  	  </select>
              </div>
              <div class="col-lg-3">
                      <select class="form-control" id="city" name="city">                      
                      @if (isset($city) || (isset($_GET['city']) && $_GET['city']> 0))
                      		<option value=0>请选择市</option>  
                       @foreach ($city as $c)  
                        	<option @if (isset($_GET['city']) && $_GET['city'] == $c->id) selected="" @endif value="{{ $c->id }}">{{ $c->name }}</option>
                       @endforeach  
                      @else
                      	<option value="0">请选择市</option>                      
                      @endif                      	
                  	  </select>
               </div>
<script>

$(document).ready(function(){	
	$('#province').change(function(){
		var province=$('#province').val();
		var url="{{ url('ajax/citylist') }}";	
		var str='';
		$.ajax({
			url: url,
			type: 'POST',
			data:{id:province},
			dataType: 'JSON',
			error: function () {  },
			success: function(data){
				str+='<option value="-1">所在市</option>';
				$.each(data,function(i,val){
					 str+="<option value="+val.id+">"+val.name+"</option>";
				})
				$('#city').html(str);
			}
		});
	 });
	
	});	                      
                      
</script>               
                    <div class="col-lg-6">
                    <input type="hidden" name="search" value="1">
                  	<input type="text" placeholder="医院名" class="form-control  " style="float:left;width:220px" name="name" value="{{ $_GET['name'] or ''}}">             
                    <button class="btn btn-default" style="float:right" type="submit"><i class="fa fa-search"></i></button>
                 </div>                 
                </div>
                </form>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>医院名称</th>
                  <th>医院等级</th>
                  <th>所在城市</th>
                  <th>排序</th>
                  <th>操作</th>
                </tr>                            
                @foreach ($data as $hospital)    	                  		
	    			<tr>
	                  <td>{{ $hospital['id'] }}</td>
	                  <td>{{ $hospital['name'] }}</td>
	                  <td>{{ $hospital['grade'] }}</td>
	                  <td>{{ $hospital['city'] }}</td>
	                  <td>{{ $hospital['sort'] }}</td>
	                  <td>
	                  		<a href="{{ url('org/orgedit',['id'=>$hospital['id']]) }}"><button class="btn bg-orange margin" type="button">编辑</button></a>
							<a href="javascript:if(confirm('确实要删除吗?'))location='{{ url('org/orgdelete',['id'=>$hospital['id']]) }}'"><button class="btn bg-maroon margin" type="button">删除</button></a>
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
