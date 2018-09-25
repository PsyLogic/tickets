@extends('layouts.app')

@section('title')
	User Management - Ticket System
@endsection

@section('pageLevelStyle')
	<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	User Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Users </span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase"> User Management</span>
		</div>
	</div>
	<div class="portlet-body">
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
		@endif
		<table class="table table-striped table-bordered table-hover " id="usersTable">
			<thead>
			 <tr>
				 <th>ID</th>
				 <th>Name</th>
				 <th>Email</th>
				 <th>Ticket</th>
				 <th>Action</th>
			 </tr>
			</thead>
			<tbody>
				
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
				<h4 class="modal-title">Edit User</h4>
			</div>
			{!! Form::open(array('id'=>'userUpdate', 'class' => 'form-horizontal')) !!}
			<div class="modal-body">Loading...</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn dark btn-outline left">Close</button>
				<button type="submit" class="btn btn-success left">Update</button>
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
<script src="{{ asset('assets/global/plugins/bootbox/bootbox.min.js') }}" ></script>
<script src="{{ asset('assets/pages/scripts/ui-blockui.min.js') }}" type="text/javascript"></script>
@endsection

@section('script')

<script>
	var table;
$(function() {
	 table = $('#usersTable').dataTable({
	 processing: true,
	 serverSide: true,
	{{--"deferLoading": '{{ $total }}',--}}
	 ajax: '{{ URL::route("admin.user.index") }}',
	 columns: [
		{ data: 'id', name: 'users.id' },
		{ data: 'name', name: 'name' },
		{ data: 'email', name: 'email' },
		{ data: 'ticketNo', name: 'tickets.subject' },
		{ data: 'action', name: 'action', orderable: false, searchable: false },
	 ]
	});



	$('#userUpdate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		var id = $('input[name=id]').val();
		var url='{{ URL::route('admin.user.update',['#id']) }}';
		url = url.replace('#id', id);

		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#userUpdate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#userUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModel').modal('hide');
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#userUpdate');
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
	$('#editModel').find('.modal-body').html('Loading..');
	hideErrors();

	$("#editModel").modal('show');
	var url='{{ URL::route('admin.user.edit',['#id']) }}';
	url = url.replace('#id', id);

	$.ajax({
		method: 'GET',
		url:url ,
		success: function (response) {
			$('#editModel').find('.modal-body').html(response);
		},
	});
}
	function deleteUser( id,name){
		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('admin.user.destroy', ['#id']) }}';
				url = url.replace('#id', id);

				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:"#usersTable",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('#usersTable');
						showToastr('success', data.message, 'success!');
						table._fnDraw();
					}
				});
			}
		});
	}
</script>

@endsection
