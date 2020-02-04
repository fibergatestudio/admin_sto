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
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>СТО - Административная панель</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ url('global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ url('css/custom.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<!-- Color picker -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
	<!-- /color picker -->

	<!-- Core JS files -->
	<script src="{{ url('global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ url('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->
	
	<!-- Statistics analysis JS files -->
	<script type="text/javascript">		
		var strHref = document.location.href;
		//если мы не находимся на странице statistics_analysis
		if(!(strHref.indexOf('statistics_analysis') + 1)){ 
			var dataTableJson = [];
		}
	</script>
	<script src="{{ url('assets/js/summary_table.js') }}"></script>
	<!-- /statistics analysis JS files -->

	<script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
	<script type="text/javascript" src="//cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>


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
	<div class="navbar navbar-expand-md navbar-dark align-items-center">
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
			

			<!-- Вывод fio работников у кого сегодня и завтра день рождения, видят все кроме работников у которых сегодня и завтра ДР-->
			@php
                            $dateToday = date("m-d");
                            $namesToday = ''; // имена тех у кого сегодня ДР
                            $namesTomorrow = ''; // имена тех у кого завтра ДР
                            foreach(App\Employee::getBirthdayToday() as $employeeToday){                     
                                $namesToday .= $employeeToday->fio.', ';                                 
                            }
                            foreach(App\Employee::getBirthdayTomorrow() as $employeeTomorrow){                      
                                $namesTomorrow .= $employeeTomorrow->fio.', ';                                  
                            }
                            $textToday = "Сегодня День рождения у \n". $namesToday;
                            $textTomorrow = "Завтра День рождения у \n". $namesTomorrow;
                        @endphp
                        
                        <!-- Вариант 1  - без Алерта 
                        
                        <ul>  
                            @if($namesToday != '')
                                @if(App\Employee::get_employee_by_user_id(Auth::user()->id) != $employeeToday)                                
                                <li><span>{{$textToday}}</span></li>
                                @endif                           
                            @endif

                            @if($namesTomorrow != '')
                                @if(App\Employee::get_employee_by_user_id(Auth::user()->id) != $employeeTomorrow)                                
                                <li><span>{{$textTomorrow}}</span></li>
                                @endif                           
                            @endif	
                        </ul>
                        -->
                        <!-- Вариант 2  - с Алертом, но после закрытия Алерта, уведомление будет выскакивать при каждом обновлении страницы -->
                        @if($namesToday != '')
                            @if(App\Employee::get_employee_by_user_id(Auth::user()->id) != $employeeToday)
                                <div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-teal border-teal alert-dismissible" style="color: #000">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <span>{{$textToday}}</span>
                                </div>    
                            @endif                           
                        @endif
                        @if($namesTomorrow != '')
                            @if(App\Employee::get_employee_by_user_id(Auth::user()->id) != $employeeTomorrow)
                                <div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-teal border-teal alert-dismissible" style="color: #000">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <span>{{$textTomorrow}}</span>
                                </div>                            
                            @endif                           
                        @endif
                       
                        
		</div>
            @if(Auth::user()->isAdmin())
                <div class="text-welcome">
                    <span>Привет, admin!</span>                    
                </div>
            @endif
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
					@elseif(Auth::user()->isClient())
						@include('limitless_parts.sidebar_client')
				@elseif(Auth::user()->isMaster())
					@include('limitless_parts.sidebar_master')
				@endif
			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div 
				@if (Request::is('admin/finances/*'))
					style="float:right";
				@endif
				class="page-header-content header-elements-md-inline">
					<div 
					@if (Request::is('admin/finances/*'))
						style="padding:0px";
					@endif
					class="page-title d-flex">
						
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


</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
<script>
	$('#cp2').colorpicker();
</script>

@yield('custom_scripts')
</html>