@extends('app')

@section('htmlheader_title')
@section('contentheader_title','首页')
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
							<th>会员数</th>
							<th>订单数</th>
						</tr>
						</thead>

						<tbody>
						<tr>
							<td>总数</td>
							<td>{{$data['member']}}</td>
							<td>{{$data['order']}}</td>
						</tr>
						<tr>
							<td>今日</td>
							<td>{{$data['member_day']}}</td>
							<td>{{$data['order_day']}}</td>

						</tr>
						<tr>
							<td>昨日</td>
							<td>{{$data['member_yesterday']}}</td>
							<td>{{$data['order_yesterday']}}</td>

						</tr>
						<tr>
							<td>本周</td>
							<td>{{$data['member_monday']}}</td>
							<td>{{$data['order_monday']}}</td>
						</tr>
						<tr>
							<td>本月</td>
							<td>{{$data['member_month']}}</td>
							<td>{{$data['order_month']}}</td>
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
