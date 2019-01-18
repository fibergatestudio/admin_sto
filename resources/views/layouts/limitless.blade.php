{{-- Секции в этом шаблоне: --}}
{{-- page_name - название страницы, "завёрнуто" в h2 --}}
{{-- content - основной контент в wrapper'е --}}
{{-- custom_scripts - место под кастомные скрипты, перед закрывающим тегом body --}}

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>СТО - Административная панель</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ url('global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ url('global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ url('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ url('global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
	<script src="{{ url('assets/js/app.js') }}"></script>
	<script src="{{ url('global_assets/js/demo_pages/dashboard.js') }}"></script>
	<!-- /theme JS files -->


	{{-- Vue.JS --}}
	{{-- development version, includes helpful console warnings --}}
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	{{-- End Vue.JS--}}

	{{-- Typeahead --}}
	
		{{-- JS --}}
		<script src="{{ url('js/typeahead.bundle.js') }}"></script>

		{{-- CSS --}}
		<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.10.6/jquery.typeahead.css" rel="stylesheet" type="text/css">
		
	{{-- /end typeahead --}}
	
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a href="/" class="d-inline-block">
                <h5>СТО</h5>
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

				<li class="nav-item dropdown">
					

					<div class="dropdown-menu dropdown-content wmin-md-350">
						

						

						
					</div>
				</li>
			</ul>



		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">
                @if(Auth::user()->isAdmin())
					@include('limitless_parts.sidebar_admin')
				@elseif(Auth::user()->isEmployee())
					@include('limitless_parts.sidebar_employee')
				@elseif(Auth::user()->isSupplyOfficer())
					@include('limitless_parts.sidebar_supply_officer')
				@endif
					@elseif(Auth::user()->isClient())
						@include('limitless_parts.sidebar_client')
			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						
                        {{-- Заголовок страницы --}}
                        <h4>
                            <span class="font-weight-semibold">
                                @yield('page_name')
                            </span>
                        </h4>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					
				</div>

				{{-- Хлебные крошки --}}
				{{-- <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div> --}}
				{{-- Конец хлебных крошек --}}
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
				
                @yield('content')

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	@yield('custom_scripts')
</body>
</html>