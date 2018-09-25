@extends('layouts.app')

@section('title')
	Priorities Management - Ticket System
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	Priorities Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Setting</span>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Priorities </span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase"> Priorities Management</span>
		</div>
		<div class="actions">
			<div class="btn-group btn-group-devided" >
				<a href="javascript:;" onclick="addModal()" class="btn sbold green"> Create Priority <i class="fa fa-plus"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="portlet-body">
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
		@endif
		<table class="table table-striped table-bordered table-hover " id="priorityTable">
			<thead>
			 <tr>
				 <th>ID</th>
				 <th>Name</th>
				 <th style="width: 33%;">Color</th>
				 <th>Action</th>
			 </tr>
			</thead>
			<tbody>
				@foreach($priorityData as $row)
					<tr >
						<td>{{$row->id}}</td>
						<td>{{$row->name}}</td>
						<td>
							<div class="input-group color colorpicker-default" data-color="{{ $row->color }}" data-color-format="rgba">
								{{ $row->color }}
								<span class="input-group-btn">
									<button class="btn default" type="button">
									  <i style="background-color: {{ $row->color }};"></i>&nbsp;
									</button>
								</span>
						   </div>
						</td>
						<td>
							<button class="btn btn-success btn-sm" data-pk="{{ $row->id }}" onclick="showModal({{ $row->id }})">Edit</button>
                            <button onclick="deletePriority('{{ $row->id }}','{{ $row->name }}')" class="btn red btn-sm">Delete</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('modal')
<div id="editModal" class="modal" tabindex="-1" data-width="400">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Edit Priorities</h4>
			</div>
			{!! Form::open(array('id'=>'priorityUpdate', 'class' => 'form-horizontal')) !!}
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
			{!! Form::open(array('id'=>'priorityCreate','class'=>'form-horizontal')) !!}
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
	 table = $('#priorityTable').dataTable({
	 processing: true,
	 serverSide: true,
	 "deferLoading": '{{ $total }}',
	 ajax: '{{ URL::route("admin.priority.index") }}',
	 columns: [
		{ data: 'id', name: 'id' },
		{ data: 'name', name: 'name' },
		{ data: 'color', name: 'color' },
		{ data: 'action', name: 'action', orderable: false, searchable: false },
	 ]
	});

	$('#priorityUpdate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		var id = $('input[name=id]').val();
		var url='{{ URL::route('admin.priority.update',['#id']) }}';
		url = url.replace('#id', id);

		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);

				App.blockUI({ target:"#priorityUpdate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#priorityUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModal').modal('hide')
				showToastr('success', response.message, 'success!');
				table._fnDraw();

			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#priorityUpdate');
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
	$('#priorityCreate').submit(function (e) {
		e.preventDefault();
		var thisForm = $(this);

		$.ajax({
			method: 'post',
			url: '{{ URL::route('admin.priority.store') }}',
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({target:"#priorityCreate",boxed:!0});
			},
			success: function (response) {
				App.unblockUI('#addForm');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#addModal').modal('hide')
				showToastr('success', response.message, 'success!');
				table._fnDraw();
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#priorityCreate');
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
		 $('#editModal').find('.modal-body').html('Loading..');
		 $('#addModal').find('.modal-body').html('Loading..');
		 hideErrors();

		 $("#addModal").modal('show');
		 $.ajax({
			 method: 'GET',
			 url:'{{ URL::route('admin.priority.create') }}',
			 success: function (response) {
				 $('#addModal').find('.modal-body').html(response);
			 },
		 });
	 }

	 function  showModal(id){
		 $('#addModal').find('.modal-body').html('Loading..');
		 $('#editModal').find('.modal-body').html('Loading..');
		 hideErrors();

		 $("#editModal").modal('show');
		 var url='{{ URL::route('admin.priority.edit',['#id']) }}';
		 url = url.replace('#id', id);

		 $.ajax({
			 method: 'GET',
			 url:url ,
			 success: function (response) {
				 $('#editModal').find('.modal-body').html(response);
			 },
		 });
	 }

	 function deletePriority(id,name){

		 bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				 '<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		 function (result) {
			 if (result) {
				 var url = '{{ URL::route('admin.priority.destroy', ['#id']) }}';
				 url = url.replace('#id', id);

				 $.ajax({
					 url: url,
					 beforeSend: function () {
						 App.blockUI({target:"#priorityTable",boxed:!0});
					 },
					 type: "DELETE",
					 success: function (data) {
						 App.unblockUI('#priorityTable');
						 showToastr('success', data.message, 'success!');
						 table._fnDraw();
					 }
				 });
			 }
		 });
	 }
 </script>
@endsection
