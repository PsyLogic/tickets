@extends(($user->user_type =='user')?"layouts.userApp":"layouts.app")
@section('title')
	 Ticket
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	Ticket Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Ticket </span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Show</span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	@if(Session::has('message'))
		<div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button></div>
	@endif
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-layers font-dark"></i>
			<span class="caption-subject bold uppercase"> Ticket Management</span>
		</div>
		<div class="actions">
			<div class="btn-group btn-group-devided" >
				<a  href="javascript:;" id="addTicketBtn" onclick="addModal()" class="btn sbold green"> Create Ticket <i class="fa fa-plus"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="tabbable-line tabbable-custom-profile">
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#tab_1_11" data-toggle="tab"> Active Tickets <span class="badge badge-success active1"> {{ $activeTotal }} </span> </a>
		</li>
		<li>
			<a href="#tab_1_22" data-toggle="tab"> Closed Tickets <span class="badge badge-success close1">{{ $completedTotal }}</span> </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1_11">
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover " id="activeTickets">
					<thead>
					 <tr>
						 <th>ID</th>
						 <th>Subject</th>
						 <th>Status</th>
						 <th>Last Updated</th>
						 <th>Agent</th>
						 <th>Priority</th>
						 <th>Owner</th>
						 <th>Category</th>
						 <th>Action</th>
					 </tr>
					</thead>
					<tbody>
						@foreach($activeTickets as $row)
							<tr>
								<td>{{$row->id}}</td>
								<td>
									{{ ucfirst($row->subject) }}
										@if($user->user_type=='agent') <?php $count = \App\CommentStatus::getAgentCommentStatus($row->id ) ?>
											@if($count>0)
												<span class="badge badge-success">{{$count}}</span>
											@endif
										@endif
										@if($user->user_type=='user') <?php $count = \App\CommentStatus::getUserCommentStatus($row->id ) ?>
											@if($count>0)
												<span class="badge badge-success">{{$count}}</span>
											@endif
										@endif
										@if($user->user_type=='admin') <?php $count = \App\CommentStatus::getAdminCommentStatus($row->id ) ?>
											@if($count>0)
												<span class="badge badge-success">{{$count}}</span>
											@endif
										@endif
								</td>
								<td><label class="label label-sm" style="background-color: {{ $row->statusColor }}"> {{ ucfirst($row->statusName) }}</label></td>
								<td>{{ \Carbon\Carbon::now()->subMinutes( \Carbon\Carbon::now()->diffInMinutes( \Carbon\Carbon::parse($row->updated_at)))->diffForHumans() }}</td>
								<td>{{ $row->agentName }}</td>
								<td><label class="label label-sm" style="background-color: {{ $row->priorityColor }}"> {{ ucfirst($row->priorityName) }}</label></td>
								<td>{{ $row->ownerName }}</td>
								<td><label class="label label-sm" style="background-color: {{ $row->categoryColor }}"> {{ ucfirst($row->categoryName) }}</label></td>
								@can('admin')
								<td>
									<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row->id])}}"><i class="fa fa-eye"></i>View</a>
									<button  class="btn purple btn-sm margin-bottom-10 activeTicketEdit" onclick="showModal({{ $row->id }})"  data-pk="{{ $row->id }}">Edit</button>
									<button data-pk="{{ $row->id }}" data-name="{{ $row->subject }}" class="btn red btn-sm margin-bottom-10 activeTicketDelete">Delete</button>
								</td>
								@endcan
								@can('agent')
								<td>
									<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row->id])}}"><i class="fa fa-eye"></i>View</a>
								</td>
								@endcan
								@can('user')
								<td>
									<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row->id])}}"><i class="fa fa-eye"></i>View</a>
								</td>
								@endcan

							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<!--tab-pane-->
		<div class="tab-pane" id="tab_1_22">
			<div class="tab-pane active" id="tab_1_1_1">
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover " id="completedTickets">
						<thead>
						 <tr>
							 <th>ID</th>
							 <th>Subject</th>
							 <th>Status</th>
							 <th>Last Updated</th>
							 <th>Agent</th>
							 <th>Priority</th>
							 <th>Owner</th>
							 <th>Category</th>
							 <th >Action</th>
						 </tr>
						</thead>
						<tbody>
							@foreach($completedTickets as $row1)
								<tr >
									<td>{{$row1->id}}</td>
									<td> <a href="{{ URL::route('ticket.show',[$row1->id])}}" data-pk="'.{{ $row1->id }}.'" >{{ $row1->subject }}
											@if($user->user_type=='agent') <?php $count = \App\CommentStatus::getAgentCommentStatus($row1->id ) ?>
												@if($count>0)
													<span class="badge badge-success">{{$count}}
													</span>
												@endif
											@endif
											@if($user->user_type=='user') <?php $count = \App\CommentStatus::getUserCommentStatus($row1->id ) ?>
												@if($count>0)
													<span class="badge badge-success">{{$count}}
													</span>
												@endif
											@endif
											@if($user->user_type=='admin') <?php $count = \App\CommentStatus::getAdminCommentStatus($row1->id ) ?>
												@if($count>0)
													<span class="badge badge-success">{{$count}}
															</span>
												@endif
											@endif
										</a>
									</td>
									<td><label class="label label-sm" style="background-color: {{ $row1->statusColor }}"> {{ $row1->statusName }}</label></td>
									<td>{{   \Carbon\Carbon::now()->subMinutes( \Carbon\Carbon::now()->diffInMinutes( \Carbon\Carbon::parse($row1->updated_at)))->diffForHumans()}}</td>
									<td>{{ $row1->agentName }}</td>
									<td><label class="label label-sm" style="background-color: {{ $row1->priorityColor }}"> {{ $row1->priorityName }}</label></td>
									<td>{{ $row1->ownerName }}</td>
									<td><label class="label label-sm" style="background-color: {{ $row1->categoryColor }}"> {{ $row1->categoryName }}</label></td>
									@can('admin')
										<td>
											<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row1->id])}}"><i class="fa fa-eye"></i>View</a>
											<button class="btn purple btn-sm margin-bottom-10 " onclick="showModal('{{ $row1->id }}')" data-pk="{{ $row1->id }}">Edit</button>
											<button data-pk="{{ $row1->id }}" data-name="{{ $row1->subject }}" class="btn red btn-sm margin-bottom-10 completedTicketDelete">Delete</button>
										</td>
									@endcan
									@can('agent')
									<td>
										<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row1->id])}}"><i class="fa fa-eye"></i>View</a>
									</td>
									@endcan
									@can('user')
									<td>
										<a class="btn green btn-sm margin-bottom-10" href="{{ URL::route('ticket.show',[$row1->id])}}"><i class="fa fa-eye"></i>View</a>
									</td>
									@endcan
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection

@section('modal')
<div id="editModel" class="modal fade" tabindex="-1" data-width="400">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            	<h4 class="modal-title">Edit Ticket</h4>
            </div>
			{!! Form::open(array('id'=>'ticketUpdate','class'=>'form-horizontal')) !!}
			<input type="hidden" name="_method" value="put">
			<div class="modal-body" >Loading...</div>
			<div class="modal-footer">
				<button type="button" id="close" data-dismiss="modal" class="btn dark btn-outline left">Close</button>
				<button type="submit" form="ticketUpdate" class="btn btn-success left">Update</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
<div id="addModal" class="modal fade" tabindex="-1" data-width="400">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Add Ticket</h4>
			</div>
			{!! Form::open(array('id'=>'ticketCreate','class'=>'form-horizontal')) !!}
			<div class="modal-body">Loading...</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn dark btn-outline left">Close</button>
				<button type="submit" form="ticketCreate" class="btn btn-success left">Submit</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

@section('pageLevelScript')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src=" {{ asset('assets/global/plugins/datatables/datatables.min.js') }}" ></script>
<script src=" {{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" ></script>
<script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
@endsection

@section('script')
<script>
	var table;
	var table1;
var columnArray = [
		{ data: 'id', name: 'tickets.id' },
		{ data: 'subject', name: 'tickets.subject' },
		{ data: 'statusName', name: 'tickets.status_id' },
		{ data: 'updated_at', name: 'tickets.updated_at' },
		{ data: 'agentName', name: 'tickets.agent_id' },
		{ data: 'priorityName', name: 'tickets.priority_id' },
		{ data: 'ownerName', name: 'users.name' },
		{ data: 'categoryName', name: 'tickets.category_id' }
  ];
@if($user->user_type == 'admin')
	columnArray.push({ data: 'action', name: 'action', orderable: false, searchable: false });
	var url = '{{ URL::route("ticket.index") }}';
	var url1 = '{{ URL::route("ticket.completedDataTable") }}';
@endif

@if($user->user_type == 'agent')
	columnArray.push({ data: 'action', name: 'action', orderable: false, searchable: false });
	var url = '{{ URL::route('agent.ticket.agentIndex') }}';
	var url1 = '{{ URL::route("agent.ticket.CompletedTickets") }}';
@endif

@if($user->user_type == 'user')
	columnArray.push({ data: 'action', name: 'action', orderable: false, searchable: false });
	var url = '{{ URL::route("user.ticket.userIndex") }}';
	var url1 = '{{ URL::route("user.ticket.CompletedTickets") }}';
@endif

$(function() {
	table = $('#activeTickets').dataTable({
	processing: true,
	serverSide: true,
	"deferLoading": '{{ $activeTotal }}',
	ajax: url,
	columns: columnArray
	});

	$('#activeTickets').on('click','.activeTicketDelete',function( e) {
		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');
		var activeCount = $('.active1').text();
		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('ticket.destroy', ['#id']) }}';
				url = url.replace('#id', id);
				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:"#activeTickets",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('#activeTickets');
						showToastr('success', data.message, 'success!');
						activeCount =parseInt(activeCount) - 1;
						$('.active1').text(activeCount);
						table._fnDraw();
					}
				});
			}
		});
	});

	table1 = $('#completedTickets').dataTable({
	processing: true,
	serverSide: true,
	"deferLoading": '{{ $completedTotal }}',

	ajax: url1,
	columns:columnArray
	});


	$('#completedTickets').on('click','.completedTicketDelete',function( e) {
		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');
		var activeCount = $('.close1').text();
		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('ticket.destroy', ['#id']) }}';
				url = url.replace('#id', id);
				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:"#completedTickets",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('#completedTickets');
						showToastr('success', data.message, 'success!');
						activeCount =parseInt(activeCount) - 1;
						$('.close1').text(activeCount);
						table1._fnDraw();
					}
				});
			}
		});
    });

});

	$('#ticketUpdate').submit(function (e) {
		e.preventDefault();

		var id = $('input[name=id]').val();
		var url='{{ URL::route('ticket.update',['#id']) }}';
		url = url.replace('#id', id);

		var thisForm = $(this);
		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#ticketUpdate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#ticketUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModel').modal('hide')
				showToastr('success', response.message, 'success!');
				if(type =='closed'){
					table1._fnDraw();
				}
				else{
					table._fnDraw();
				}


			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#ticketUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				for (var key in xhr.responseJSON) {
					if (xhr.responseJSON.hasOwnProperty(key)) {
						var obj = xhr.responseJSON[key];
						showInputError(key, obj[0]);
					}
				}
			}
		});
	});

	$('#ticketCreate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		var activeCount = $('.active1').text();

		$.ajax({
			method: 'post',
			url: '{{ URL::route('ticket.store') }}',
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#ticketCreate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#ticketCreate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#addModal').modal('hide');
				activeCount =parseInt(activeCount) + 1;
				$('.active1').text(activeCount);
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#ticketCreate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				for (var key in xhr.responseJSON) {
					if (xhr.responseJSON.hasOwnProperty(key)) {
						var obj = xhr.responseJSON[key];
						showInputError(key, obj[0]);
					}
				}
			}
		});
	});

	function  showModal(id){
		$('#addModal').find('.modal-body').html('Loading..');
		$('#editModel').find('.modal-body').html('Loading..');
		hideErrors();

		$("#editModel").modal('show');
		var url='{{ URL::route('ticket.edit',['#id']) }}';
		url = url.replace('#id', id);

		$.ajax({
			method: 'GET',
			url:url ,
			success: function (response) {
				$('#editModel').find('.modal-body').html(response);
			},
		});
	}

	function addModal(){
		$('#editModel').find('.modal-body').html('Loading..');
		$('#addModal').find('.modal-body').html('Loading..');
		hideErrors();

		$("#addModal").modal('show');
		$.ajax({
			method: 'GET',
			url:'{{ URL::route('ticket.create') }}',
			success: function (response) {
				$('#addModal').find('.modal-body').html(response);
			},
		});
	}
</script>

@endsection
