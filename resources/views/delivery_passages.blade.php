@extends('layouts.limitless')

@section('page_name')
Проходы через систему контроля
@endsection

@section('content')

<button id="submit" class="btn btn-success">Генерировать post-данные</button>
<table class="table datatable-basic" id="summary-table">
	<thead>
		<tr>
			<th>Id в системе Sigur</th>
			<th>Время прохода</th>
			<th>Имя</th>
			<th>Табельный номер</th>
			<th>Направление</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($passages))
		@foreach($passages as $passage)
            <tr>
                <td>{{ $passage->log_id }}</td>
                <td>{{ date("Y-m-d H:i:s", $passage->time) }}</td>
                <td>{{ $passage->emp_id }}</td>
                <td>{{ $passage->internal_emp_id }}</td>
                @if($passage->direction == 1)
                <td>{{ 'вход' }}</td>
                @elseif($passage->direction == 2)
                <td>{{ 'выход' }}</td>
                @elseif($passage->direction == 3)
                <td>{{ 'неизвестно' }}</td>               
                @endif
            </tr>
        @endforeach    
		@endif
	</tbody>
</table>

{{ $passages->links() }}
<script type="text/javascript">
	
	// Генерирует тестовые POST данные о проходе через Сигур
	$('#submit').click(function (){
		var someObj = {
						"logs":"logId=18&date=2019-03-22&time=09:14:02&name1=Роман&name2=Майданский-test&direction=вход&tabnum=069017785"
					};
		$.ajax({
                    url: "{{ route('processing_query') }}",
                    type: "POST",
                    data: JSON.stringify(someObj),
                    contentType: "json",
					processData: false,
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (msg) {
                        alert('Ошибка admin');
                    }
                });
		alert("Данные сгенерированы!");
		document.location.reload(true);

	});

</script>
@endsection