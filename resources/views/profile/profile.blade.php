@extends(($user->user_type =='user')?"layouts.userApp":"layouts.app")

@section('style')
<link href="{{ asset('assets/pages/css/profile-2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
	Profile - Ticket System
@endsection

@section('page-title')
	Profile Managment
@endsection

@section('page-breadcrumb')
<li>
	<a href="{{ url('/') }}">Home</a>
	<i class="fa fa-circle"></i>
</li>

<li>
	<span class="active">Profile</span>
</li>

@endsection

@section('content')
<!-- BEGIN PAGE BASE CONTENT -->
	<div class="profile">
		<div id="profileEditMessage"></div>
		<div class="tabbable-full-width">
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1_3">
					<div class="row profile-account">
						<div class="col-md-3">
							<ul class="ver-inline-menu tabbable margin-bottom-10">
								<li class="active">
									<a data-toggle="tab" href="#tab_1-1">
										<i class="fa fa-cog"></i> Personal info </a>
									<span class="after"> </span>
								</li>
								<li>
									<a data-toggle="tab" href="#tab_3-3">
										<i class="fa fa-lock"></i> Change Password </a>
								</li>
							</ul>
						</div>
						<div class="col-md-9">
							<div class="tab-content">
								<div id="tab_1-1" class="tab-pane active">
									{!! Form::open(array('id'=>'editProfile')) !!}
										<input type="hidden" name="id" value="{{ $user->id }}" id="id">
										<div class="form-group">
											<label class="control-label">First Name</label>
											<input type="text" placeholder="First Name" name="name" class="form-control name" value="{{ $user->name }}"  id="name" />
											<span class="help-block">  </span>
										</div>
										<div class="form-group">
											<label class="control-label">Email</label>
											<input type="email" class="form-control placeholder-no-fix" name="email" value="{{ $user->email }}" placeholder="Enter Email Address" id="email" >
											<span class="help-block">  </span>
										</div>
										<div class="margiv-top-10">
											<button type="submit" class="btn green btn-sm"> Save Changes </button>
										</div>
									{!! Form::close() !!}
								</div>
								<div id="tab_3-3" class="tab-pane">
									{!! Form::open(array('id'=>'editPassword')) !!}
                                        <input type="hidden" name="idPassword" value="{{ $user->id }}" id="idPassword">
										<div class="form-group">
											<label class="control-label">Old Password</label>
											<input type="password" class="form-control" name="old_password" id="old_password" />
											 <span class="help-block">  </span>
										</div>
										<div class="form-group">
											<label class="control-label">New Password</label>
											<input type="password" class="form-control" name="new_password" id="new_password" />
											<span class="help-block">  </span>
										</div>
										<div class="form-group">
											<label class="control-label">Confirm Password</label>
											<input type="password" class="form-control"  name="confirm_password" id="confirm_password" />
											<span class="help-block">  </span>
										</div>
										<div class="margin-top-10">
											<button type="submit" class="btn green btn-sm"> Change Password </button>
										</div>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
						<!--end col-md-9-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE BASE CONTENT -->

@endsection

@section('script')
<script>

$('#editProfile').submit(function (e) {
    e.preventDefault();
    var thisForm = $(this);

    $.ajax({
        method: 'post',
        url: '{{ URL::route('ticket.profileUpdate') }}',
        dataType: 'json',
        data: thisForm.serialize(),
        beforeSend: function (xhr) {
            hideErrors();
            thisForm.find('button[type="submit"]').prop('disabled', true);
            App.blockUI({ target:"#editProfile", boxed:!0 });
        },
        success: function (response) {
            App.unblockUI('#editProfile');
            slideToElement(thisForm);
            thisForm.find('button[type="submit"]').prop('disabled', false);
			$('.username').text('Welcome '+response.username);
			showToastr('success', response.message, 'success!');
        },
        error: function (xhr, textStatus, thrownError) {
            App.unblockUI('#editProfile');
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
$('#editPassword').submit(function (e) {
    e.preventDefault();
    var thisForm = $(this);

    $.ajax({
        method: 'post',
        url: '{{ URL::route('ticket.PasswordUpdate') }}',
        dataType: 'json',
        data: thisForm.serialize(),
        beforeSend: function (xhr) {
            hideErrors();
            thisForm.find('button[type="submit"]').prop('disabled', true);
            App.blockUI({ target:"#editPassword", boxed:!0 });
        },
        success: function (response) {
            App.unblockUI('#editPassword');
            slideToElement(thisForm);
            thisForm.find('button[type="submit"]').prop('disabled', false);
			showToastr('success', response.message, 'success!');
        },
        error: function (xhr, textStatus, thrownError) {
            App.unblockUI('#editPassword');
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
</script>
@endsection