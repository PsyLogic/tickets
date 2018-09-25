<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title> @yield('title') - Ticket System</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- Fonts -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />

<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href=" {{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" type="text/css" />
<link href=" {{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />

<link href=" {{ asset('assets/pages/css/login.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="shortcut icon" href="favicon.ico" />
</head>

<body class="login">
	<div class="menu-toggler sidebar-toggler"></div>
	<div class="logo">
		<a href="javascript:;">
			<img src="{{ asset('companylogo/'.$logo->logo) }}" height="80px" alt="" />
		</a>
	</div>

    @yield('content')

	<!-- JavaScripts -->
    <!--[if lt IE 9]>
	<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
	<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script>
	<![endif]-->
	<!-- BEGIN CORE PLUGINS -->
	<script src="{{ asset('assets/global/plugins/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}"></script>
	<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="{{ asset('assets/global/scripts/app.min.js') }}"></script>
	<script src="{{ asset('assets/global/scripts/commonjs.js') }}"></script>
	<!-- END THEME GLOBAL SCRIPTS -->

	@yield('script')
</body>
</html>
