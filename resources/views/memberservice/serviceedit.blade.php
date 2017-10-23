<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

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
             	@section('contentheader_title','编辑')
             	<form class="form-horizontal" action="{{ url('memberservice/servicesave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加')
            	<form class="form-horizontal" action="{{ url('memberservice/servicecreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->title or '' }}">
                  </div>
                </div>
                  @if($type != 3)
                <div class="form-group">
                  <label class="col-sm-2 control-label" >排序</label>
                  <div class="col-sm-10">
                    <input type="text"  name="sort"  placeholder="Enter ..." class="form-control" value="{{ $data->sort or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >操作方式</label>
                  <div class="col-sm-10">
					<input name="open_type"  value="1" onclick="show_jms(0)" checked="" type="radio">详情介绍 <input name="open_type" onclick="show_jms(1)" @if (isset($data) && $data->open_type == 2) checked="" @endif  value="2"  type="radio">挂号<input name="open_type" onclick="show_jms(1)" @if (isset($data) && $data->open_type == 3) checked="" @endif  value="3"  type="radio">住院
                  </div>
                </div>
                  @endif
                <div class="form-group" id="show_content" @if (isset($data) && $data->open_type > 1) style="display:none" @endif>
                  <label class="col-sm-2 control-label" >详情</label>
                  <div class="col-sm-10">
                 	 <script id="editor" name="content" type="text/plain" style="width:1024px;height:500px;">{!! $data->content or '' !!}</script>
                 	 <input type="hidden" name="content" id="content" value="">
                  </div>
                </div>  
                
                                                                                                                                 
              </div>
				<script type="text/javascript">

				function show_jms(par){
					if(par==1){
						$('#show_content').hide();
					}else{
						$('#show_content').show();
					}
				}
				
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
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                <input type="hidden" name="type" value="{{ $type}} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
