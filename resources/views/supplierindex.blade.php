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
				<div class="box-body">
					<table id="example2" class="table table-bordered table-hover">

						<thead>
						<tr>
							<th>日期</th>
							<th>订单数</th>
						</tr>
						</thead>

						<tbody>
						<tr>
							<td>总数</td>
							<td>{{$order_num['order']}}</td>
						</tr>
						<tr>
							<td>今日</td>
							<td>{{$order_num['order_day']}}</td>

						</tr>
						<tr>
							<td>昨日</td>
							<td>{{$order_num['order_yesterday']}}</td>

						</tr>
						<tr>
							<td>本周</td>
							<td>{{$order_num['order_monday']}}</td>
						</tr>
						<tr>
							<td>本月</td>
							<td>{{$order_num['order_month']}}</td>
						</tr>
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</div>
</section>
@endsection
