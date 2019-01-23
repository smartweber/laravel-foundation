<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>BK Foundations | CRT Registration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{{ csrf_token() }}}" />
        
        <!-- CORE CSS FRAMEWORK - START -->
        <link href="{{ asset('/assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" media="screen"/>
        <link href="{{ asset('/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/bootstrap/css/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/fonts/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/fonts/circle-frame/stylesheet.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/css/animate.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS FRAMEWORK - END -->

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <link href="{{ asset('/assets/plugins/icheck/skins/square/blue.css') }}" rel="stylesheet" type="text/css" media="screen"/>
		<link href="{{ asset('/assets/plugins/datatables/css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" media="screen"/>
		<link href="{{ asset('/assets/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css') }}" rel="stylesheet" type="text/css" media="screen"/>
		<link href="{{ asset('/assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}" rel="stylesheet" type="text/css" media="screen"/>
		<link href="{{ asset('/assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" media="screen"/>
		<link href="{{ asset('/assets/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" media="screen"/>
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE CSS TEMPLATE - START -->
        <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/css/responsive.css') }}" rel="stylesheet" type="text/css"/>
        <!-- CORE CSS TEMPLATE - END -->

        <!--[if lt IE 9]>
        <script src="{{ asset('/assets/js/html5.js')}}"></script>
        <script src="{{ asset('/assets/js/respond.min.js')}}"></script>
    	<![endif]-->

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    @if (Auth::guest())
		<body class=" login_page2">
	@else
		<body class="">
	@endif
    <!-- 
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Laravel</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	-->
	@yield('content')

        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->


        <!-- CORE JS FRAMEWORK - START --> 
        <script src="{{ asset('/assets/js/jquery-1.11.2.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('/assets/js/jquery.easing.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/assets/js/bootstrap-select.js') }}" type="text/javascript"></script>  
        <script src="{{ asset('/assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>  
        <script src="{{ asset('/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}" type="text/javascript"></script> 
        <script src="{{ asset('/assets/plugins/viewport/viewportchecker.js') }}" type="text/javascript"></script>  
        <!-- CORE JS FRAMEWORK - END --> 


        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
        <script src="{{ asset('/assets/plugins/icheck/icheck.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/assets/plugins/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('/assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('/assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('/assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js') }}" type="text/javascript"></script>
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 


        <!-- CORE TEMPLATE JS - START --> 
        <script src="{{ asset('/assets/js/scripts.js') }}" type="text/javascript"></script> 
		<script src="{{ asset('/assets/plugins/autosize/autosize.min.js') }}" type="text/javascript"></script>
        <!-- END CORE TEMPLATE JS - END --> 

        <!-- Sidebar Graph - START --> 
        <script src="{{ asset('/assets/plugins/sparkline-chart/jquery.sparkline.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/assets/js/chart-sparkline.js') }}" type="text/javascript"></script>
        <!-- Sidebar Graph - END -->
        
        <!-- General modals -->
        @yield('modals');
        <!-- General modals end-->
	</body>
	<script>
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			error: function(event, request, options, error) {
	        	switch (event.status) 
	        	{
	            	case 401: window.location.href = "{{ url('/auth/login') }}"; 
	            	break;
	        	}
	        }
		});
	</script>
</html>
