<script src="{{ asset('/js/jquery.min.js') }}"></script>



@extends('app')

@section('htmlheader_title')
    Home
@endsection
    
@section('brade_line')
 @parent
<li><a href="{{ url('orgclasslist') }}"><i class="fa "></i>医疗机构</a></li>
<li><a href="{{ url('orgsecondclasslist',['id'=>$cid]) }}"><i class="fa "></i>医疗机构二级分类</a></li>
<li><a href="{{ url('orglist',['id'=>$cid]) }}"><i class="fa "></i>医疗机构列表</a></li>            
@stop
@section('main-content')
         
 
            @if (session('message'))  
            
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                	{{ session('message') }}
              </div>
            @endif
<script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.config.js') }} "></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('/plugins/ueditor/ueditor.all.min.js') }}"> </script>            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
             @if (isset($data) )
             	@section('contentheader_title','编辑医疗机构')
             	<form class="form-horizontal" action="{{ url('org/orgsave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加医疗机构')
            	<form class="form-horizontal" action="{{ url('org/orgcreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >医院名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">所在城市</label>
                  <div class="col-sm-2 ">
                      <select class="form-control" id="province">
                      	<option>请选择省</option>
                       @foreach ($province as $p)  
                        	<option @if (isset($data) &&  $data->province == $p->id) selected="" @endif value="{{ $p->id }}">{{ $p->name }}</option>
                       @endforeach  
                  	  </select>
                  </div>
                  <div class="col-sm-2 ">
                      <select class="form-control" id="city" name="city">
                      @if (isset($data) &&  $data->city > 0) 
                       @foreach ($city as $c)  
                        	<option @if ($data->city == $c->id) selected="" @endif value="{{ $c->id }}">{{ $c->name }}</option>
                       @endforeach  
                      @else
                      	<option value="0">请选择市</option>                      
                      @endif                      	
                  	  </select>
                  </div>                  
                </div>                  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >详细地址</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="address" placeholder="Enter ..." value="{{ $data->address or '' }}">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-2 control-label" >联系电话</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="phone" placeholder="Enter ..." value="{{ $data->phone or '' }}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >医院等级</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="grade" placeholder="Enter ..." value="{{ $data->grade or '' }}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >医院类型</label>
                  <div class="col-sm-10">
                       @foreach ($class as $cla)
                        	<input name="hospital_class[]" type="checkbox" @if (isset($data) && $cla['checked'] == 1) checked="" @endif value="{{ $cla['id'] }}">{{ $cla['name'] }}
                       @endforeach  
                  </div>
                </div>                 
                
  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >医院介绍</label>
                  <div class="col-sm-10">
                 	 <script id="editor" type="text/plain" style="width:1024px;height:500px;">{!! $data->content or '' !!}</script>
                 	 <input type="hidden" name="content" id="content" value="">
                  </div>
                </div>                 
                <div class="form-group">
                  <label class="col-sm-2 control-label" >排序</label>
                  <div class="col-sm-10">
                    <input type="text"  name="sort"  placeholder="Enter ..." class="form-control" value="{{ $data->sort or '' }}">
                  </div>
                </div>                  
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">LOGO</label>
                  <div class="col-sm-10">
                     <input type="file" name="image">
                     	@if (isset($data) && $data->logo!='')
                     		<a target="_blank" href="{{ $data->logo }}"><img width="200" height="100" src="{{ $data->logo }}" ></a>
                     	@endif
                  </div>
                </div>                 
				<script type="text/javascript">
				    //实例化编辑器
				    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
				    var ue = UE.getEditor('editor');
				    
				    

				    ue.addListener('blur',function(){        
						var con=UE.getEditor('editor').getContent();
						$('#content').val(con);        
				    });

				    function isFocus(e){
				        alert(UE.getEditor('editor').isFocus());
				        UE.dom.domUtils.preventDefault(e)
				    }
				    function setblur(e){
				        UE.getEditor('editor').blur();
				        UE.dom.domUtils.preventDefault(e)
				    }
				    function insertHtml() {
				        var value = prompt('插入html代码', '');
				        UE.getEditor('editor').execCommand('insertHtml',value )
				    }
				    
				</script>  
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
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                <input type="hidden" name="cid" value="{{ $cid}} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
