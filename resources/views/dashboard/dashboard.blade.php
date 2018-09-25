@extends('layouts.app')

@section('title')
	 Dashboard
@endsection

@section('page-title')
	Dashboard & statistics
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Dashboard</span>
	</li>
@endsection

@section('content')
<!-- BEGIN DASHBOARD STATS 1-->
{{--Agent Type --}}
@if($user->user_type == 'agent')
<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat blue">
				<div class="visual">
					<i class="fa fa-comments"></i>
				</div>
				<div class="details">
					<div class="number">
						<span> {{ $user->agentCreatedTickets() }}</span>
					</div>
					<div class="desc">Created Tickets </div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat red">
				<div class="visual">
					<i class="fa fa-bar-chart-o"></i>
				</div>
				<div class="details">
					<div class="number">
						<span>{{ $user->agentAssignedTickets() }}</span>
					</div>
					<div class="desc">Assigned Tickets </div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat green">
				<div class="visual">
					<i class="fa fa-shopping-cart"></i>
				</div>
				<div class="details">
					<div class="number">
						<span>{{ $user->agentAllTickets('closed') }}</span>
					</div>
					<div class="desc">Completed Tickets</div>
				</div>
			</div>
		</div>
</div>
<div class="row">

		<div class="col-md-6 col-sm-6">
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption caption-md">
						<i class="icon-bar-chart font-red"></i>
						<span class="caption-subject font-red bold uppercase">Agent Activity</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable table-scrollable-borderless">
						<table class="table table-hover table-light">
							<thead>
							<tr class="uppercase">
								<th> Subject </th>
								<th> Owner </th>
								<th> Created </th>
								<th> CLOSED </th>
							</tr>
							</thead>
							@foreach($agentData as $row)
								<tr>
									<td>{{ substr($row->subject,0,10)}}</td>
									<td>{{ $row->ownerName }}</td>
									<td> {{ $row->created_at->format('d-M-Y') }} </td>
									<td> {{ $row->updated_at->format('d-M-Y') }} </td>
								</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>

</div>
@endif
{{--End Agent Type --}}
@if($user->user_type == 'admin')
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
		<div class="dashboard-stat blue">
			<div class="visual">
				<i class="fa fa-comments"></i>
			</div>
			<div class="details">
				<div class="number">
					<span>{{ $allTickets }}</span>
				</div>
				<div class="desc"> Total Tickets </div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat red">
			<div class="visual">
				<i class="fa fa-bar-chart-o"></i>
			</div>
			<div class="details">
				<div class="number">
					<span>{{ $activeTickets }}</span> </div>
				<div class="desc">Active Tickets </div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat green">
			<div class="visual">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<div class="details">
				<div class="number">
					<span >{{ $completedTickets }}</span>
				</div>
				<div class="desc">Closed Tickets</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div id="chartMessage"></div>
	<div class="row">

			<div class="col-md-6">
 				<!-- BEGIN INTERACTIVE CHART PORTLET-->
				<div class="portlet light portlet-fit bordered" style="height: 500px;">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-bar-chart font-green"></i>
							<span class="caption-subject font-green sbold uppercase">Performance Indicator </span>
						</div>
						<div class="actions">
							<div class="btn-group">
								<a class="btn green sbold uppercase btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Periods
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="javascript:;" id="months_1" name="">3 months</a>
									</li>
									<li>
										<a href="javascript:;" id="months_2">6 months</a>
									</li>
									<li>
										<a href="javascript:;" id="months_3">12 months</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="portlet-body">
						<div id="chart_2" class="chart"> </div>
					</div>
				</div>
				<!-- END INTERACTIVE CHART PORTLET-->
			</div>
			<div class="col-md-6">
				<div class="portlet light portlet-fit bordered">
					<div class="portlet-title">
						<div class="caption">
							<i class="ico font-green"></i>
							<span class="caption-subject font-green sbold uppercase">Ticket counter </span>
						</div>
					</div>
					<div class="portlet-body" >
						<div class="tabbable-custom nav-justified">
							<ul class="nav nav-tabs nav-justified">
								<li @if(Request::has('page_a') || (Request::has('page_a') == false && Request::has('page_b') == false && Request::has('page_c') == false)) class="active" @endif>
									<a href="#tab_1_1_1" data-toggle="tab"> Categories </a>
								</li>
								<li @if(Request::has('page_b')) class="active" @endif>
									<a href="#tab_1_1_2" data-toggle="tab"> Agents </a>
								</li>
								<li @if(Request::has('page_c')) class="active" @endif>
									<a href="#tab_1_1_3" data-toggle="tab"> Users </a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane @if(Request::has('page_a') || (Request::has('page_a') == false && Request::has('page_b') == false && Request::has('page_c') == false)) active @endif" id="tab_1_1_1">
									<div class="mt-element-list">
										<div class="mt-list-head list-simple font-dark bg-default">
												<div class="list-date">Open/Close</div>
												<p class="list-title">Category <span class="badge badge-default bold">Total</span></p>
										</div>
										<div class="mt-list-container list-simple" style="border: none;">
											<ul>
												@foreach( $categoryTicket as $category)
													<li class="mt-list-item">
														<div class="list-datetime">{{ $category->getTicket('opened') }} / {{ $category->getTicket('closed') }}</div>
														<div class="list-item-content" style="padding:0px;">
															<p class="uppercase">
																{{ $category->name }}
																<span class="badge badge-default bold">{{ $category->getTicket('opened') + $category->getTicket('closed') }}</span>
															</p>
														</div>
													</li>
												@endforeach
											</ul>
											{!! $categoryTicket->links() !!}
										</div>
									</div>
								</div>
								<div class="tab-pane fade @if(Request::has('page_b')) active @endif" id="tab_1_1_2">
									<div class="mt-element-list">
										<div class="mt-list-head list-simple font-dark bg-default">
												<div class="list-date">Open/Close</div>
												<p class="list-title">Agent <span class="badge badge-default bold">Total</span></p>
										</div>
										<div class="mt-list-container list-simple" style="border: none;">
											<ul>
												@foreach( $agentTicket as $agents)
													<li class="mt-list-item">
														<div class="list-datetime">{{ $agents->totalAgentTickets('opened') }} / {{ $agents->totalAgentTickets('closed') }}</div>
														<div class="list-item-content" style="padding:0px;">
															<p class="uppercase">
																{{ $agents->name }}
																<span class="badge badge-default bold">{{ $agents->totalAgentTickets('opened') + $agents->totalAgentTickets('closed') }}</span>
															</p>
														</div>
													</li>
												@endforeach
											</ul>
											{!! $agentTicket->links() !!}
										</div>
									</div>
								</div>
								<div class="tab-pane fade @if(Request::has('page_c')) active @endif" id="tab_1_1_3">
									<div class="mt-element-list">
										<div class="mt-list-head list-simple font-dark bg-default">
												<div class="list-date">Open/Close</div>
												<p class="list-title">User <span class="badge badge-default bold">Total</span></p>
										</div>
										<div class="mt-list-container list-simple" style="border: none;">
											<ul>
												@foreach( $userTicket as $users)
														<li class="mt-list-item">
															<div class="list-datetime">{{ $users->totalUserTickets('opened') }} / {{ $users->totalUserTickets('closed')}}</div>
															<div class="list-item-content" style="padding:0px;">
																<p class="uppercase">
																	{{ $users->name }}
																	<span class="badge badge-default bold">{{ $users->totalUserTickets('opened') + $users->totalUserTickets('closed')}}</span>
																</p>
															</div>
														</li>
												@endforeach
											</ul>
											{!! $userTicket->links() !!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

	</div>
<div class="row">
		<div class="col-md-6">
			<div class="portlet light portlet-fit bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class=" icon-layers font-green"></i>
						<span class="caption-subject font-green bold uppercase">Tickets share per category </span>
					</div>
				</div>
				<div class="portlet-body">
					<div id="piechart" style="height:300px;"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="portlet light portlet-fit bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class=" icon-layers font-green"></i>
						<span class="caption-subject font-green bold uppercase">Tickets share per Agent </span>
					</div>
				</div>
				<div class="portlet-body">
					<div id="agentChart" style="height:300px;"></div>
				</div>
			</div>
		</div>
</div>
@endif
<!-- END DASHBOARD STATS 1-->
@endsection

@section('pageLevelScript')

<script src="{{ asset('assets/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/flot/jquery.flot.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/flot/jquery.flot.categories.js') }}" type="text/javascript"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
@endsection
@section('script')
<script>

	var ChartsFlotcharts = function () {
		return {
			//main function to initiate the module
			initCharts: function (data) {
				var arr = [];
				arr = data;
				if (data == null) {
					arr = {!! $value !!};
				}
				if (!jQuery.plot) {
					return;
				}

				function chart2() {
					if ($('#chart_2').size() != 1) {
						return;
					}
					var chartData = [];
					$.each(arr, function (key, value) {
						chartData.push({
							data: value,
							label: key,
							lines: {
								show: true,
								lineWidth: 1
							},
							title:'How Many Tickets Resolved Per Month'
						})
					});
					var plot = $.plot($("#chart_2"), chartData, {
						series: {
							lines: {
								show: true,
								lineWidth: 2,
								fill: true,
								fillColor: {
									colors: [{
										opacity: 0.05
									}, {
										opacity: 0.01
									}]
								}
							},
							points: {
								show: true,
								radius: 3,
								lineWidth: 1
							},
							shadowSize: 2
						},
						grid: {
							hoverable: true,
							clickable: true,
							tickColor: "#eee",
							borderColor: "#eee",
							borderWidth: 1
						},
						colors: ["#d12610", "#37b7f3", "#52e136"],
						xaxis: {
							mode: "categories",
							minTickSize: [1, "months"],
						},
						yaxis: {
							ticks: 12,
							tickDecimals: 0,
							tickColor: "#eee"
						}
					});

					function showTooltip(x, y, contents) {
						$('<div id="tooltip">' + contents + '</div>').css({
							position: 'absolute',
							display: 'none',
							top: y + 5,
							left: x + 15,
							border: '1px solid #333',
							padding: '4px',
							color: '#fff',
							'border-radius': '3px',
							'background-color': '#333',
							opacity: 0.80
						}).appendTo("body").fadeIn(200);
					}

					var previousPoint = null;
					$("#chart_2").bind("plothover", function (event, pos, item) {
						$("#x").text(pos.x.toFixed());
						$("#y").text(pos.y.toFixed());

						if (item) {
							if (previousPoint != item.dataIndex) {
								previousPoint = item.dataIndex;

								$("#tooltip").remove();
								var x = item.datapoint[0].toFixed(),
										y = item.datapoint[1].toFixed();

								showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
							}
						} else {
							$("#tooltip").remove();
							previousPoint = null;
						}
					});
				}
				chart2();
			}
		};
	}();

	jQuery(document).ready(function () {
		ChartsFlotcharts.initCharts();
	});

$("#months_1,#months_2,#months_3").click(function(){
	var text =$(this).text();
	var intStr1 = parseInt(text.replace(/[A-Za-z$-]/g, ""));
	$.ajax({
		method: 'get',
		url: '{{ URL::route('dashboard') }}',
		dataType: 'json',
		data:{month:intStr1},
		beforeSend: function (xhr) {
		App.blockUI({ target:".page-header-fixed ", boxed:!0 });
		},
		success: function (response) {
			ChartsFlotcharts.initCharts(response);
			App.unblockUI('.page-header-fixed ');
		},
	});
})
//pie Chart
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['category', 'share per category'],
			@foreach($pieData as $data)
				{!! $data.',' !!}
			@endforeach
		]);

		var options = {
			title: 'Tickets distribution per category'
		};

		var chart = new google.visualization.PieChart(document.getElementById('piechart'));
		chart.draw(data, options);
	}
	//pie Chart
	google.charts.setOnLoadCallback(drawAgentChart);
	function drawAgentChart() {
		var data = google.visualization.arrayToDataTable([
			['Agent', 'share per agent'],
			@foreach($agentPieData as $data)
				{!! $data.',' !!}
			@endforeach
		]);

		var options = {
			title: 'Tickets distribution per Agent'
		};

		var chart = new google.visualization.PieChart(document.getElementById('agentChart'));

		chart.draw(data, options);
	}
</script>

@endsection
