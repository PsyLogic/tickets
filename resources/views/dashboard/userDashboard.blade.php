@extends('layouts.userApp')

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
	@if($user->user_type == 'user')
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

				<div class="dashboard-stat grey-mint">
					<div class="visual">
						<i class="fa fa-bar-chart-o"></i>
					</div>
					<div class="details">
						<div class="number"> {{ $user->userCreatedTickets() }} </div>
						<div class="desc"> Created Tickets </div>
					</div>
					<a class="more" href="{{ URL::route('user.ticket.userIndex') }}""> View more
						<i class="m-icon-swapright m-icon-white"></i>
					</a>
				</div>

			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

				<div class="dashboard-stat green-seagreen">
					<div class="visual">
						<i class="fa fa-bar-chart-o"></i>
					</div>
					<div class="details">
						<div class="number"> {{ $user->totalUserTickets('opened') }} </div>
						<div class="desc"> Active Tickets </div>
					</div>
					<a class="more" href="{{ URL::route('user.ticket.userIndex') }}"> View more
						<i class="m-icon-swapright m-icon-white"></i>
					</a>
				</div>

			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

				<div class="dashboard-stat blue-steel">
					<div class="visual">
						<i class="fa fa-bar-chart-o"></i>
					</div>
					<div class="details">
						<div class="number"> {{ $user->totalUserTickets('closed') }} </div>
						<div class="desc"> Closed Tickets </div>
					</div>
					<a class="more" href="{{ URL::route('user.ticket.userIndex') }}"> View more
						<i class="m-icon-swapright m-icon-white"></i>
					</a>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart font-red"></i>
							<span class="caption-subject font-red bold uppercase">User Activity</span>
							<span class="caption-helper">weekly stats...</span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-scrollable table-scrollable-borderless">
							<table class="table table-hover table-light">
								<thead>
								<tr class="uppercase">
									<th> Subject </th>
									<th> Created </th>
									<th> CLOSED </th>
								</tr>
								</thead>
								@foreach($userData as $row)
									<tr>
										<td>{{ substr($row->subject,0,10)}}</td>
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
				<!-- END DASHBOARD STATS 1-->
@endsection

@section('pageLevelScript')
	<script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
@endsection
