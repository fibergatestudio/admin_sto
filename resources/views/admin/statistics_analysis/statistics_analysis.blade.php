@extends('layouts.limitless')

@section('page_name')
    Список графиков
@endsection

@section('content')


<div class="container">
	<!-- Stacked lines -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Доходы работников</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<div class="chart-container">
				<div class="chart has-fixed-height" id="line_stacked"></div>
			</div>
		</div>
	</div>
	<!-- /stacked lines -->

	<!-- Stacked clustered columns -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Количество выполненных нарядов</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<div class="chart-container">
				<div class="chart has-fixed-height" id="columns_clustered"></div>
			</div>
		</div>
	</div>
	<!-- /stacked clustered columns -->

	<!-- Pie chart timeline -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Статистика услуг</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<div class="chart-container">
				<div class="chart has-fixed-height" id="pie_timeline"></div>
			</div>
		</div>
	</div>
	<!-- /pie chart timeline -->

	<!-- Basic datatable -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Сводная таблица</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<code>Сводная таблица по сотрудникам</code> : количество опозданий, штрафов с возможностью выбрать период
		</div>
		
		<div class="dataTables_wrapper no-footer">
			<div class="datatable-header">            
				<div class="row form-group">
					<h5>Период:</h5>
					<div class="col-6 col-md-4">
						<h5>от</h5>
						<select name="fromMonth" id="fromMonth" class="my-2 form-control-sm form-control">
							@if(isset($months))
							@for($i=0; $i < count($months); $i++)
							<option value="{{$i}}">{{ $months[$i] }}</option>
							@endfor
							@endif
						</select>
						<select name="fromYear" id="fromYear" class="my-2 form-control-sm form-control">
							@if(isset($years))
							@for($i=0; $i < count($years); $i++)
							<option value="{{$years[$i]}}">{{ $years[$i] }}</option>
							@endfor
							@endif
						</select>
					</div>
					<div class="col-6 col-md-4">
						<h5>до</h5>
						<select name="untilMonth" id="untilMonth" class="my-2 form-control-sm form-control">
							@if(isset($months))
							@for($i=0; $i < count($months); $i++)
							<option value="{{$i}}">{{ $months[$i] }}</option>
							@endfor
							@endif
						</select>
						<select name="untilYear" id="untilYear" class="my-2 form-control-sm form-control">
							@if(isset($years))
							@for($i=0; $i < count($years); $i++)
							<option value="{{$years[$i]}}">{{ $years[$i] }}</option>
							@endfor
							@endif
						</select>
					</div>
					<div class="input-group-btn">    
						<button id="makeTable" class="btn btn-primary btn-sm">Построить таблицу</button>
					</div>
				</div>
			</div>

			<table class="table datatable-basic" id="summary-table">
				<thead>
					<tr>
						<th>Фамилия</th>
						<th>Кол-во опозданий</th>
						<th>Кол-во штрафов</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	<!-- /basic datatable -->
</div>


@endsection

@section('custom_scripts')

<!-- This theme JS files -->
<script type="text/javascript">var dataTableJson = JSON.parse('<?=$data_table_json ?>');</script>
	<script src="{{ url('global_assets/js/plugins/visualization/echarts/echarts.min.js') }}"></script>
	<script src="{{ url('assets/js/charts.js') }}"></script>
<!-- /this theme JS files -->

@endsection