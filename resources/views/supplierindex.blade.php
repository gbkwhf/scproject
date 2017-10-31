@extends('app')

@section('htmlheader_title')
@section('contentheader_title',"欢迎，$data->name")
    Home
@endsection


@section('main-content')
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">数据统计</h3>
				</div>
				<!-- /.box-header -->
						
				<!-- /.box-body -->
			</div>
		</div>
	</div>
</section>
@endsection
