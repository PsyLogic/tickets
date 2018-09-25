@extends('layouts.app')

@section('title')
    Email Template - Ticket System
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Email Templates
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
        <span class="active">Email Templates </span>
    </li>
@endsection

@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase">Email Templates</span>
            </div>
        </div>
        <div class="portlet-body">
            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
            @endif
            <table class="table table-striped table-bordered table-hover " id="emailTemplateTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($emailTemplateData as $row)
                    <tr >
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->subject }}</td>
                    <td>{!! $row->content  !!} </td>
                    <td>
                        <button class="btn btn-success btn-sm " onclick="showModal('{{ $row->id }}')" data-pk="{{ $row->id }}">Edit</button>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Email Template</h4>
                </div>
                {!! Form::open(['id'=>'emailTemplateUpdate', 'class' => 'form-horizontal']) !!}
                <div class="modal-body">Loading...</div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
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
<script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
@endsection
@section('script')
    <script>
        var table;
        $(function() {
                table = $('#emailTemplateTable').dataTable({
                processing: true,
                serverSide: true,
                "deferLoading": '{{ $total }}',
                ajax: '{{ URL::route("emailtemplate.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'subject', name: 'subject' },
                    { data: 'content', name: 'content' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#emailTemplateUpdate').submit(function (e) {
                e.preventDefault();
                var thisForm = $(this);

                var id = $('input[name=id]').val();
                var url='{{ URL::route('emailtemplate.update',['#id']) }}';
                url = url.replace('#id', id);

                $.ajax({
                    method: 'put',
                    url: url,
                    dataType: 'json',
                    data: thisForm.serialize(),
                    beforeSend: function (xhr) {
                        hideErrors();
                        thisForm.find('button[type="submit"]').prop('disabled', true);
                        App.blockUI({ target:"#emailTemplateUpdate", boxed:!0 });
                    },
                    success: function (response) {
                        App.unblockUI('#emailTemplateUpdate');
                        slideToElement(thisForm);
                        thisForm.find('button[type="submit"]').prop('disabled', false);
                        $('#editModal').modal('hide');
                        showToastr('success', response.message, 'success!');
                        table._fnDraw();
                    },
                    error: function (xhr, textStatus, thrownError) {
                        App.unblockUI('#emailTemplateUpdate');
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
            $('#editModal').find('.modal-body').html('Loading..');
            hideErrors();
            var url = '{{ URL::route('emailtemplate.edit',['#id']) }}';
            url = url.replace('#id', id);

            $("#editModal").modal('show');

            $.ajax({
                method: 'GET',
                url:url ,
                success: function (response) {
                    $('#editModal').find('.modal-body').html(response);
                },
            });
        }
    </script>
@endsection

