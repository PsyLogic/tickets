@extends('layouts.app')

@section('title')
    General Settings - Ticket System
@endsection

@section('pageLevelStyle')

    {!! Html::style("assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css")!!}
    {!! Html::style('assets/global/plugins/croper/croper.css') !!}
@endsection

@section('page-title')
    General Settings
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
        <span class="active">Create </span>
    </li>
@endsection

@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Settings</span>
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('id'=>'setting','class'=>'form-horizontal','method'=>'post','route'=>"admin.setting.store",'files' => true)) !!}
            <div class="form-body">
                <input type="hidden" name="id" value="{{$editData->id}}">
                <div class="form-group">
                    <label class="col-md-2 control-label">Upload Logo</label>
                    <div class="col-md-8">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ asset('companylogo/'.$editData->logo) }}" alt=""/> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                            <div>
                                <span class="btn default btn-file">
                                <span class="fileinput-new"> Select image </span>
                                <span class="fileinput-exists"> Change </span>
                                <input id="file" name="logo" type="file"> </span>
                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                            </div>

                            <span> Maximum Image size 2KB </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Company Name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Company Name" name="name" id="name" value="{{ $editData->company_name }}">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Email</label>
                    <div class="col-md-8">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ $editData->email }}">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Address</label>
                    <div class="col-md-8">
                        <textarea  class="form-control" placeholder="Address" name="address" id="address" >{{ $editData->address }}</textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Phone</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Phone Number" name="phone" id="phone" value="{{ $editData->phone }}">
                        <span class="help-block"></span>
                    </div>
                </div>
            </div><hr>
            <div class="form-actions fluid">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <button type="button" class="btn default">Cancel</button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('pageLevelScript')
    {!! Html::script("assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js")!!}
@endsection


@section('script')
    <script>
        $(document).ready(function (e) {
            $("#setting").on('submit', (function (e) {
                e.preventDefault();
                var thisForm = $(this);
                $.ajax({
                    type: 'POST',
                    url: $('#setting').attr('action'),
                    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData: false,
                    beforeSend: function (xhr) {
                        hideErrors();
                        thisForm.find('button[type="submit"]').prop('disabled', true);

                        App.blockUI({ target:"#setting", boxed:!0 });
                    },
                    success: function (response) {
                        App.unblockUI('#setting');
                        thisForm.find('button[type="submit"]').prop('disabled', false);
                        $('.logo-default').prop('src',response.image).css({"width":120,"height":20});

                        showToastr('success', response.message, 'success!');
                    },
                    error: function (xhr, textStatus, thrownError) {
                        App.unblockUI('#setting');
                        thisForm.find('button[type="submit"]').prop('disabled', false);
                        for (var key in xhr.responseJSON) {
                            if (xhr.responseJSON.hasOwnProperty(key)) {
                                var obj = xhr.responseJSON[key];
                                showInputError(key, obj[0]);
                            }
                        }
                    }
                });
            }));
        });
    </script>

@endsection
