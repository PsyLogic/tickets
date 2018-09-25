@extends('layouts.appauth')

@section('title')
Login
@endsection

@section('content')
<div class="content">
	{!! Form::open(array('id'=>'form', 'class' => 'login-form')) !!}
		<h3 class="form-title font-green">Sign In</h3>

		<div class="" id="error"></div>

		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Email Address</label>
			<input type="email" class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Email Address" id="email" name="email" value="{{ old('email') }}">
			<span class="help-block"></span>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<input type="password" class="form-control form-control-solid placeholder-no-fix" placeholder="Password" name="password" id="password">
			<span class="help-block"></span>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn green uppercase">Login</button>
			<label class="rememberme check">
				<input type="checkbox" name="remember" value="1" />Remember
			</label>
			<a href="{{ url('password/email') }}"class="forget-password" name="forgetpassword">Forgot Password?</a>
		</div>
		<div class="create-account">
			<p>
				<a href="{{ url('/register') }}" class="uppercase" name="createaccount">Create an account</a>
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
			url: "{{ url('/login') }}", // Url to which the request is send
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