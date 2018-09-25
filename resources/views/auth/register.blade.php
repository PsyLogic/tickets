@extends('layouts.appauth')
@section('title')
	Register
@endsection

@section('content')
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	{!! Form::open(['id'=>'form', 'method' => 'post', 'class' => 'login-form']) !!}
	<h3 class="font-green">Sign Up</h3>

	<div class="" id="error"></div>

	<p class="hint"> Enter your personal details below: </p>
	<div class="alert alert-danger display-hide"><button class="close" data-close="alert"></button></div>

	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">Name</label>
		<input type="text" class="form-control placeholder-no-fix" name="name" id="name" value="" placeholder="Enter Name">
        <span class="help-block"></span>
	</div>

	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">E-Mail Address</label>
		<input type="email" class="form-control placeholder-no-fix" name="email" id="email" value=""placeholder="Enter Email Address">
        <span class="help-block"></span>
	</div>

	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<input type="password" class="form-control placeholder-no-fix" name="password" id="password" placeholder="Enter Password">
        <span class="help-block"></span>
	</div>

	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9"> Confirm Password </label>
		<input type="password" class="form-control placeholder-no-fix" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
		<span class="help-block"> </span>
	</div>

	<div class="form-actions">
		<button type="submit" id="register-submit-btn" name="register" class="btn btn-success">Submit</button>
	</div>
	<div class="create-account">
		<p>
			<a href="{{ url('/login') }}" name="haveacc" class="uppercase">Already have Account</a>
		</p>
	</div>
	{!! Form::close() !!}
</div>
@endsection
@section('script')

<script>
$(document).ready(function() {
	$("#form").submit(function(event) {
		event.preventDefault();
		hideErrors();
		$.ajax({
			url: "{{ url('/register') }}", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			beforeSend : function () {
				showResponseMessage({status : 'responsePending', message : 'Submitting...'}, 'error');
			},
			error: function (data) {
				hideMessage('error', 'alert,alert-info');
				showResponseMessage({status : 'fail', errors : data.responseJSON});
			},
			success: function(data) {
				hideMessage('error', 'alert,alert-info,alert-danger');
				showResponseMessage(data, 'error');
			}
		});
	});
});
</script>
@endsection
