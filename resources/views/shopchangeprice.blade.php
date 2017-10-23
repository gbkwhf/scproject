
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

@section('main-content')
         
 
            @if (session('message'))                 
                <div class="tools-alert tools-alert-green">
                    {{ session('message') }}
                </div>
            @endif
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->

             	@section('contentheader_title','批量修改服务价格')
             	<form class="form-horizontal" action="{{ url('shop/changepricesave') }}" method="post"  >
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >需要修改的城市</label>
                  <div class="col-sm-10">
    <link href="{{ asset('/plugins/dtree/dtree.css') }}" rel="stylesheet" type="text/css" />
   	<script src="{{ asset('/plugins/dtree/checkTree.js') }}" type="text/javascript"></script>
   	<script src="{{ asset('/plugins/dtree/operateAjax.js') }}" type="text/javascript"></script>
					<div class="dtree">
						<SCRIPT LANGUAGE="JavaScript">
						                  <!--
						var dt="{{ asset('/plugins/dtree/dtree/') }}";					                  
						d = new dTree('d');
						d.add(0,-1,'省市列表');
						@foreach ($treeList as $vo)
						d.add('{{$vo->id}}','{{$vo->parentId}}','{{$vo->name}}','1','dept','');
						@endforeach
						d.draw();
						//-->
						</SCRIPT>	
					</div>         	
                  </div>
                </div>
				<input type="hidden" id="optAjaxData" name="check_data"/>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >价格</label>
                  <div class="col-sm-10">
                    <input type="text"  name="price"  placeholder="Enter ..." class="form-control" value="">
                  </div>
                </div> 
                                                                                                                                                                                           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
@endsection
