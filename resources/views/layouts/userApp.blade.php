
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title> @yield('title') - Ticket System</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <link href="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        @yield('pageLevelStyle')
		<!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        @yield('themeGlobalStyle')
        <link href="{{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ asset('assets/layouts/layout3/css/layout.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/layouts/layout3/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ asset('assets/layouts/layout3/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->
    <body class="page-container-bg-solid page-boxed">
        <!-- BEGIN HEADER -->
        <div class="page-header">
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="javascript:;">
                            <img src="{{ asset('companylogo/'.$logo->logo) }}" height="60px" alt="" />
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler"></a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a name="tologout" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

                                    <span class="username"> Welcome {!! ucfirst($user->name) !!}</span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">

                                    <li>
                                        <a href="{{ URL::route('ticket.profile') }}">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li>
                                        <a name="logout" href="{{ url('logout') }}">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER TOP -->
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container">
                    <!-- BEGIN MEGA MENU -->
                    <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                    <div class="hor-menu ">
                        <ul class="nav navbar-nav">
                            <li class="menu-dropdown classic-menu-dropdown {{ $dashActive or '' }}">
                                <a href="{{ url('/') }}"> <i class="icon-speedometer"></i> Dashboard
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            @if($user->user_type=="user")
								<li class="menu-dropdown mega-menu-dropdown {{ $activeUser or '' }} ">
									<a href="{{ URL::route('user.ticket.userIndex') }}" class="nav-link nav-toggle">
										<i class="icon-layers"></i>
										<span class="title">Tickets</span>
									</a>
								</li>
								<li class="menu-dropdown classic-menu-dropdown {{ $activeOpenProfile or '' }} ">
									<a href="{{ URL::route('ticket.profile') }}" class="nav-link nav-toggle">
									<i class="icon-user"></i>
									<span class="title">Profile</span>
									</a>
								</li>
                            @endif
                        </ul>
                    </div>
                    <!-- END MEGA MENU -->
                </div>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <!-- BEGIN PAGE HEAD-->
                <div class="page-head">
                    <div class="container">
                        <!-- BEGIN PAGE TITLE -->
                        <div class="page-title">
                            <h1>@yield('page-title')</h1>
                        </div>
                        <!-- END PAGE TITLE -->
                        <!-- BEGIN PAGE TOOLBAR -->
                        <div class="page-toolbar">

                        </div>
                        <!-- END PAGE TOOLBAR -->
                    </div>
                </div>
                <!-- END PAGE HEAD-->
                <!-- BEGIN PAGE CONTENT BODY -->
                <div class="page-content">
                    <div class="container">
                        <!-- BEGIN PAGE BREADCRUMBS -->
                        <ul class="page-breadcrumb breadcrumb">
                           @yield('page-breadcrumb')
                        </ul>
                        <!-- END PAGE BREADCRUMBS -->
                        <!-- BEGIN PAGE CONTENT INNER -->
                        <div class="page-content-inner">
                           @yield('content')
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                    </div>
                </div>
                <!-- END PAGE CONTENT BODY -->
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container"> {{ Carbon\Carbon::now()->format('Y') }} © Ticket System.

            </div>
        </div>
        <!-- END FOOTER -->
        @yield('modal')
        <!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js')}}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js')}}"></script>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
		<script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        @yield('themeLevelScript')
        <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
         @yield('pageLevelScript')
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/layouts/layout3/scripts/demo.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/scripts/commonjs.js') }}"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">
            App.setAssetsPath('{{ asset('/assets') }}/');
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            @if(Session::has('toastrType'))
                showToastr('{{ session('toastrType') }}', '{{ session('toastrMessage') }}')
            @endif
        });
        </script>
        @yield('script')
    </body>

</html>