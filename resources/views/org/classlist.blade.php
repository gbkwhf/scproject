@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title','医疗机构管理')

@section('brade_line')
 @parent
<li><a href="{{ url('orgclasslist') }}"><i class="fa "></i>医疗机构</a></li>         
@stop

    

@section('main-content')

<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>分类名称</th>
                </tr> 
                           
                @foreach ($data as $class)    	                  		
	    			<tr>
	                  <td>{{ $class['id'] }}</td>
	                  <td><a href="{{ url('orgsecondclasslist',['id'=>$class['id']]) }}">{{ $class['name'] }}</a>
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
