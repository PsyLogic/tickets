<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<head>
<meta charset="utf-8" />
<title> @yield('title') - Ticket System</title>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{ asset('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<link href="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
@yield('pageLevelStyle')
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="{{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
<link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
@yield('style')
<!-- END THEME LAYOUT STYLES -->
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
</head>
<!-- END HEAD -->
<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">
	<div class="page-header navbar navbar-fixed-top">
		<div class="page-header-inner ">
			<div class="page-logo">
				<a href="{{ route('dashboard') }}">
				   	<img src="{{ asset('companylogo/'.$logo->logo) }}"alt="logo" height="20px" width="120px" class="logo-default" />
				</a>
				<div class="menu-toggler sidebar-toggler"></div>
		   </div>
		   <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
			<div class="page-top">
				<div class="top-menu">
					<ul class="nav navbar-nav pull-right">
						<li class="separator hide"> </li>
						<li class="dropdown dropdown-user dropdown-dark">
							<a name="tologout" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<span class="username username-show-on-mobile"> Welcome {!! ucfirst($user->name) !!} </span>
								<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu dropdown-menu-default">
								<li>
									<a href="{{ URL::route('ticket.profile') }}">
										<i class="icon-user"></i> My Profile </a>
								</li>
								<li>
									<a name="logout" href="{{ url('logout') }}"><i class="icon-key"></i>Logout</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"> </div>
	<div class="page-container">
		<div class="page-sidebar-wrapper">
			<div class="page-sidebar navbar-collapse collapse">
				<ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
					<li class="nav-item  {{ $dashActive or '' }}">
						<a href="{{ url('/') }}" class="nav-link nav-toggle">
							<i class="icon-home"></i>
							<span class="title">Dashboard</span>
							<span class="selected"></span>
						</a>
					</li>
					@if($user->user_type=="agent")
						<li class="nav-item {{ $activeAgent or '' }}">
							<a href="{{ URL::route('agent.ticket.agentIndex') }}" class="nav-link nav-toggle">
								<i class="icon-layers"></i>
								<span class="title">Ticket</span>
							</a>
						</li>
						<li class="nav-item {{ $activeOpenProfile or '' }}">
							<a href="{{ URL::route('ticket.profile') }}" class="nav-link nav-toggle">
								<i class="icon-user"></i>
								<span class="title">Profile</span>
							</a>
						</li>
					@endif
					@if($user->user_type=="admin")
						<li class="nav-item {{ $activeOpenTicket or '' }}">
							<a href="{{URL::route('ticket.index') }}" class="nav-link nav-toggle">
								<i class="icon-layers"></i>
								<span class="title">Ticket</span>
							</a>
						</li>
						<li class="nav-item {{ $activeOpenProfile or '' }}">
							<a href="{{ URL::route('ticket.profile') }}" class="nav-link nav-toggle">
								<i class="icon-user"></i>
								<span class="title">Profile</span>
							</a>
						</li>
						<li class="nav-item {{ $activeUser or '' }} ">
							<a href="javascript:;" class="nav-link nav-toggle">
								<i class="icon-settings"></i>
								<span class="title">User Types</span>
								<span class="arrow {{ $openUser or '' }}"></span>
							</a>
							<ul class="sub-menu">
								<li class="nav-item {{ $activeOpenAdmin or '' }}">
									<a href="{{ URL::route('admins.index') }}" class="nav-link ">
										<i class="icon-user"></i>
										<span class="title">Admin</span>
									</a>
								</li>
								<li class="nav-item {{ $activeOpenAgent or '' }} ">
									<a href="{{ URL::route('admin.agent.index') }}" class="nav-link ">
										<i class="icon-user"></i>
										<span class="title">Agent</span>
									</a>
								</li>
								<li class="nav-item {{ $activeOpenUser or '' }} ">
									<a href="{{ URL::route('admin.user.index') }}" class="nav-link ">
										<i class="icon-user"></i>
										<span class="title">User</span>
									</a>
								</li>
							</ul>
						</li>

					@endif

					@can('admin')
					<li class="nav-item {{ $active or '' }} ">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="icon-settings"></i>
							<span class="title">Settings</span>
							<span class="arrow {{ $open or '' }}"></span>
						</a>
						<ul class="sub-menu">
							<li class="nav-item {{ $activeOpenStatus or '' }} ">
								<a href="{{ URL::route('admin.status.index') }}" class="nav-link ">
									<span class="title">Status</span>
								</a>
							</li>
							<li class="nav-item {{ $activeOpenPriority or '' }} ">
								<a href="{{ URL::route('admin.priority.index') }}" class="nav-link ">
									<span class="title">Priorities</span>
								</a>
							</li>
							<li class="nav-item {{ $activeOpenCategory or '' }} ">
								<a href="{{ URL::route('admin.category.index') }}" class="nav-link ">
									<span class="title">Categories</span>
								</a>
							</li>
							<li class="nav-item {{ $setting or '' }} ">
								<a href="{{ URL::route('admin.setting.create') }}" class="nav-link ">
									<span class="title">General Settings</span>
								</a>
							</li>
							<li class="nav-item {{ $activeEmailTemplate or '' }} ">
								<a href="{{ URL::route('emailtemplate.index') }}" class="nav-link ">
									<span class="title">Email Templates</span>
								</a>
							</li>
						</ul>
					</li>
					@endcan
				</ul>
			</div>
		</div>
		<div class="page-content-wrapper">
			<div class="page-content">
				 <div class="page-head">
					<!-- BEGIN PAGE TITLE -->
					<div class="page-title">
						<h1>
						@yield('page-title')
						</h1>
					</div>
				</div>
				<ul class="page-breadcrumb breadcrumb">
					@yield('page-breadcrumb')
				</ul>
				@yield('content')
			</div>
		</div>

	</div>

@yield('modal')
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- END CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<!-- BEGIN THEME GLOBAL SCRIPTS -->
@yield('themeLevelGlobalScript')
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/plugins/bootbox/bootbox.min.js') }}" ></script>
<script src="{{ asset('assets/pages/scripts/ui-blockui.min.js') }}" type="text/javascript"></script>
	@yield('pageLevelScript')
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/commonjs.js') }}"></script>
<script>
$(function() {
	$.ajaxSetup({
	  headers: {
		  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	App.setAssetsPath('{{ asset('/assets') }}/');

	@if(Session::has('toastrType'))
		showToastr('{{ session('toastrType') }}', '{{ session('toastrMessage')}}', '{{ session('toastrTitle')}}')
	@endif
});

function errorShow(data){
	$.each(data, function (k,v){
		$('#'+k).closest('.form-group').addClass('has-error');
        $('#'+k).closest('.form-group').find('.help-block').first().text(v);
	});
}
</script>
@yield('script')
	<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>