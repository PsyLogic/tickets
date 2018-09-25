@extends('layouts.app')

@section('title')
	Agents Management - Ticket System
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	Agents Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Agents </span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase"> Agents Management</span>
		</div>
		<div class="actions">
			<div class="btn-group btn-group-devided">
				<a href="javascript:;"  class="btn sbold green" onclick="addModal()"> Create Agent <i class="fa fa-plus"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="portlet-body">
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
		@endif
		<table class="table table-striped table-bordered table-hover " id="agentsTable">
			<thead>
			 <tr>
				 <th>ID</th>
				 <th>Name</th>
				 <th>Email</th>
				 <th style="width: 33%">Categories</th>
				 <th>Action</th>
			 </tr>
			</thead>
			<tbody>
				@foreach($agentData as $row)
					<tr >
						<td>{{$row->id}}</td>
						<td>{{ $row->userName.' '.$row->last_name }}</td>
						<td>{{$row->email}}</td>
						<td>{{$row->categoryNames}}</td>
						<td>
							<button class="btn btn-success btn-sm " onclick="showModal({{ $row->id }})"  data-pk="{{ $row->id }}">Edit</button>
							<button onclick="deleteAgent('{{ $row->id }}','{{ $row->userName }}')" class="btn btn-danger btn-sm">Delete</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('modal')
	<div id="editModel" class="modal fade" tabindex="-1" data-width="400">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Edit Agent</h4>
				</div>
				{!! Form::open(array('id'=>'agentUpdate', 'class' => 'form-horizontal')) !!}
				<input type="hidden" name="_method" value="put">
				<div class="modal-body" >Loading...</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline left">Close</button>
					<button type="submit" class="btn btn-success left">Update</button>
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
					<h4 class="modal-title">Add Agent</h4>
				</div>
				{!! Form::open(array('id'=>'agentCreate','class'=>'form-horizontal')) !!}
				<div class="modal-body">Loading...</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection

@section('pageLevelScript')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src=" {{ asset('assets/global/plugins/datatables/datatables.min.js') }}" ></script>
<script src=" {{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" ></script>
@endsection

@section('script')
<script>
	var table;

$(function() {
	 table =  $('#agentsTable').dataTable({
	 processing: true,
	 serverSide: true,
	"deferLoading": '{{ $total }}',
	 ajax: '{{ URL::route("admin.agent.index") }}',
	 columns: [
		{ data: 'id', name: 'users.id' },
		{ data: 'userName', name: 'users.name' },
		{ data: 'email', name: 'users.email' },
		{ data: 'categoryNames', name: 'categories.name' },
		{ data: 'action', name: 'action', orderable: false, searchable: false },
	 ]
	});

	$('#agentCreate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		$.ajax({
			method: 'post',
			url: '{{ URL::route('admin.agent.store') }}',
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#agentCreate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#agentCreate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#addModal').modal('hide');
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#agentCreate');
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


	$('#agentUpdate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		var id = $('input[name=id]').val();
		var url='{{ URL::route('admin.agent.update',['#id']) }}';
		url = url.replace('#id', id);

		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#agentUpdate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#agentUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModel').modal('hide');
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#agentUpdate');
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
});

	function  showModal(id){
		$('#addModal').find('.modal-body').html('Loading..');
		$('#editModel').find('.modal-body').html('Loading..');
		hideErrors();

		$('.select2-selection__choice').remove();

		var url = '{{ URL::route('admin.agent.edit',['#id']) }}';
		url = url.replace('#id', id);

		$("#editModel").modal('show');

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
			url:'{{ URL::route('admin.agent.create') }}',
			success: function (response) {
				$('#addModal').find('.modal-body').html(response);
			},
		});
	}

	function deleteAgent( id,name){
		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('admin.agent.destroy', ['#id']) }}';
				url = url.replace('#id', id);

				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:"#agentsTable",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('#agentsTable');
						showToastr('success', data.message, 'success!');
						table._fnDraw();
					}
				});
			}
		});
	}
</script>
@endsection
