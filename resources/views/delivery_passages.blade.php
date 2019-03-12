@extends('layouts.limitless')

@section('page_name')
Проходы через систему контроля
@endsection

@section('content')

<button id="submit" class="btn btn-success">Генерировать post-данные</button>
<table class="table datatable-basic" id="summary-table">
	<thead>
		<tr>
			<th>logId</th>
			<th>time</th>
			<th>empId</th>
			<th>internalEmpId</th>
			<th>direction</th>
			<th>created_at</th>
			<th>updated_at</th>
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
                <td>{{ $passage->direction }}</td>
                <td>{{ $passage->created_at }}</td>
                <td>{{ $passage->updated_at }}</td>
            </tr>
        @endforeach    
		@endif
	</tbody>
</table>

{{ $passages->links() }}
<script type="text/javascript">
	$('#submit').click(function (){
		var someObj = {
						"logs":[
						{
						"logId":925244,
						"time":1510826281,
						"empId":"",
						"internalEmpId":0,
						"accessPoint":1,
						"direction":2,
						"keyHex":"5AB860"
						},
						{
						"logId":925247,
						"time":1510826858,
						"empId":"",
						"internalEmpId":0,
						"accessPoint":1,
						"direction":2,
						"keyHex":"5AB860"
						}
						]
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