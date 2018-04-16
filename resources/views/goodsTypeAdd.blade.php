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

        @section('contentheader_title','添加商品类型')

        <form class="form-horizontal" action="{{url('goods/type/save')}}" method="post"  enctype ="multipart/form-data">


                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品类型名称</label>
                        <div class="col-sm-10">
                            <input type="text"  name="goodsTypeName"  placeholder="Enter ..." class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >商品排序</label>
                        <div class="col-sm-10">
                            <input type="text"  name="sort"  placeholder="Enter ..." class="form-control" value="255">
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEmail3">商品规格</label>
                        <div class="col-sm-10">

                            @if(!empty($data))
                                @foreach($data as $k=>$v)

                                    <input type="checkbox" name="spec" value="{{$v->id}}" />{{$v->name}}
                                @endforeach
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

                    	$('#change_class').change(function(){
                    		var first_id=$('#change_class').val();
                    		var url="{{ url('ajax/getgoodsclass') }}";	
                    		var str='';
                    		$.ajax({
                    			url: url,
                    			type: 'POST',
                    			data:{id:first_id},
                    			dataType: 'JSON',
                    			error: function () {  },
                    			success: function(data){
                    				$.each(data,function(i,val){
                    					 str+="<option value="+val.id+">"+val.name+"</option>";
                    				})
                    				$('#class_list').html(str);
                    			}
                    		});
                    	 });

                        function fun(){
                            obj = document.getElementsByName("spec");
                           check_val = [];
                            //check_val = "";
                            for(k in obj){
                                if(obj[k].checked)
                                    check_val.push(obj[k].value);
                                    //check_val += obj[k].value+",";
                            }
                            //alert(check_val);
                            document.getElementById("union").value = check_val;
                        }

                    </script>

                <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" name="id" value="{{ $data->id or '' }} ">
                    <input  id="union"  type="hidden" name="spec_arr" value="">
                    {{--<input type="hidden" name="type" value="{{ $type}} ">--}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button class="btn btn-info pull-right" type="submit"  onclick="fun()">提交</button>
                </div>
                <!-- /.box-footer -->
            </form>
    </div>
@endsection
