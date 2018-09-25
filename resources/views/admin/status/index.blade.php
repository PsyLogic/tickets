@extends('layouts.app')

@section('title')
	Status Management
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	Status Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Settings</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Status</span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase"> Status Management</span>
		</div>
		<div class="actions">
			<div class="btn-group btn-group-devided" >
				<a href="javascript:;" class="btn sbold green" onclick="addModal()"> Create Status <i class="fa fa-plus"></i></a>
			</div>
		</div>
	</div>
	<div class="portlet-body">
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
		@endif
		<table class="table table-striped table-bordered table-hover" id="statusTable">
			<thead>
			 <tr>
				 <th>ID</th>
				 <th>Name</th>
				 <th style="width: 33%;">Color</th>
				 <th>Action</th>
			 </tr>
			</thead>
			<tbody>
				@foreach($statusData as $row)
					<tr>
						<td>{{ $row->id }}</td>
						<td>{{ $row->name }}</td>
						<td>
							<div class="input-group color" data-color="{{ $row->color }}" data-color-format="rgba">
								{{ $row->color }}
								<span class="input-group-btn">
									<button class="btn default" type="button">
									  <i style="background-color: {{ $row->color }};"></i>&nbsp;
									</button>
								</span>
						   </div>
						</td>
						<td>
						@if(strtolower($row->name) != 'pending')
							<button type="button" class="btn btn-success btn-sm " onclick="showModal({{ $row->id }})" data-pk="{{ $row->id }}">Edit</button>
                            <button onclick="deleteStatus('{{ $row->id }}','{{ $row->name }}')" class="btn red btn-sm">Delete</button>
						@endif
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
					<h4 class="modal-title">Edit Status</h4>
				</div>
				{!! Form::open(['id'=>'editForm', 'class' => 'form-horizontal', 'method' => 'put']) !!}
				<div class="modal-body">Loading...</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
					<button type="submit" class="btn btn-success">Update</button>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>

<div id="addModal" class="modal" tabindex="-1" data-width="400">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Add Status</h4>
			</div>
			{!! Form::open(['id'=>'addForm', 'class' => 'form-horizontal']) !!}
			<div class="modal-body">Loading...</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
				<button type="submit" class="btn btn-success">Add</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

@section('pageLevelScript')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" ></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" ></script>
<script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
@endsection

@section('script')
<script>
	var table;
$(function() {
	table = $('#statusTable').dataTable({
		processing: true,
		serverSide: true,
		"deferLoading": '{{ $total }}',
		ajax: '{{ URL::route("admin.status.index") }}',
		columns: [
			{data: 'id', name: 'id'},
			{data: 'name', name: 'name'},
			{data: 'color', name: 'color'},
			{data: 'action', name: 'action', orderable: false, searchable: false}
		]
	});

	$('#addForm').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		$.ajax({
			method: 'post',
			url: '{{ URL::route('admin.status.store') }}',
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#addForm", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#addForm');
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#addModal').modal('hide');
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#addForm');
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

	$('#editForm').submit(function (e) {
		e.preventDefault();

		var id = $('input[name=id]').val();
		var url='{{ URL::route('admin.status.update',['#id']) }}';
		url = url.replace('#id', id);

		var thisForm = $(this);

		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.closest('form').serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#editForm", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#editForm');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModel').modal('hide');
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#editForm');
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
	function addModal(){
		$('#editModel').find('.modal-body').html('Loading..');
		$('#addModal').find('.modal-body').html('Loading..');
		hideErrors();

		$("#addModal").modal('show');
		$.ajax({
			method: 'GET',
			url:'{{ URL::route('admin.status.create') }}',
			success: function (response) {
				$('#addModal').find('.modal-body').html(response);
			},
		});
	}

	function  showModal(id){
		$('#addModal').find('.modal-body').html('Loading..');
		$('#editModel').find('.modal-body').html('Loading..');
		hideErrors();

		$('#editModel').modal('show');
		var url='{{ URL::route('admin.status.edit',['#id']) }}';
		url = url.replace('#id', id);

		$.ajax({
			method: 'GET',
			url:url ,
			success: function (response) {
				$('#editModel').find('.modal-body').html(response);
			},
		});
	}
	function deleteStatus( id,name){
        bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
                '<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('admin.status.destroy', ['#id']) }}';
				url = url.replace('#id', id);

				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target: "#statusTable", boxed: !0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('#statusTable');
						showToastr('success', data.message, 'success!');
						table._fnDraw();
					}
				});
			}
		});
	}
 </script>
@endsection
