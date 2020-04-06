@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')

<style type="text/css">

.content { padding: 0; }
#table-calculation-profitability { padding: 1.25rem 1.25rem; }
.tabs-menu { width: 100%; padding: 0px; margin: 0 auto; }
.tabs-menu>input { display:none; }
.tabs-menu>div {
    display: none;
    padding: 12px;
    border: 1px solid #C0C0C0;
    text-align: center;
}
.tabs-menu>label {
    display: inline-block;
    padding: 7px;
    margin: 0 -5px -1px 0;
    text-align: center;
    color: #666666;
    border: 1px solid #C0C0C0;
    background: #E0E0E0;
    cursor: pointer;
    width: 20.11%;
}
.tabs-menu>input:checked + label {
    color: #000000;
    border: 1px solid #C0C0C0;
    border-bottom: 1px solid #f2f2f2;
    background: #f2f2f2;
}
#tab_1:checked ~ #txt_1,#tab_2:checked ~ #txt_2{ display: block; }

</style>


<div class="mt-3 card card-p">
    <h3>В данный момент на работе</h3>
    <table class="table datatable-basic">
        <thead>
            <tr>
                <th>Id в системе Sigur</th>
                <th>Время прохода</th>
                <th>Имя</th>
                <th>Табельный номер</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($employees_now))
            @foreach($employees_now as $employee)
            <tr>
                <td>{{ $employee->log_id }}</td>
                <td>{{ date("Y-m-d H:i:s", $employee->time) }}</td>
                <td>{{ $employee->emp_id }}</td>
                <td>{{ $employee->internal_emp_id }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>


<div class="tabs-menu">

    <input type="radio" name="inset" value="" id="tab_1" checked>
    <label for="tab_1">СКУД</label>

    <input type="radio" name="inset" value="" id="tab_2">
    <label for="tab_2">Кофе</label>

    <div id="txt_1">

        <button id="submit" class="btn btn-success">Генерировать post-данные</button>
        <div class="mt-3 card card-p">
            <table class=" table datatable-basic" id="summary-table">
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
        </div>
    
    {{ $passages->links() }}
    </div><!-- txt_1 -->

    <div id="txt_2">
        
        <a href="/coffee.php?phone=069017666-test" class="btn btn-success">Генерировать get-данные coffee</a>
        <div class="mt-3 card card-p">
            <table class=" table datatable-basic">
                <thead>
                <tr>
                    <th>Время получения кофе</th>
                    <th>Имя</th>
                    <th>Табельный номер</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($employees_coffee))
                    @foreach($employees_coffee as $employee)
                        <tr>
                            <td>{{ date("Y-m-d H:i:s", strtotime($employee->created_at)) }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->phone }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    
    </div><!-- txt_2 -->

</div><!-- tabs-menu -->


<div class="mt-3 card card-p">
    <h3>Нарушители</h3>
    <table class="table datatable-basic">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Табельный номер</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($employees_intruder))
            @foreach($employees_intruder as $employee)
            <tr>
                <td>{{ $employee->general_name }}</td>
                <td>{{ $employee->phone }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>


<script type="text/javascript">

	// Генерирует тестовые POST данные о проходе через Сигур
	$('#submit').click(function (){
        var date = new Date(Date.now()).getFullYear();
        date += '-'+ (new Date(Date.now()).getMonth()+1);
        date += '-'+ (new Date(Date.now()).getDate());
		/*var someObj = {
						"logs":"logId=19&date="+date+"&time=09:15:02&name1=Иван&name2=Сидоров-test&direction=выход&tabnum=069017666-test"
					};*/
        var someObj = {
                        "logs":"logId=18&date="+date+"&time=09:15:02&name1=Роман&name2=Майданский-test&direction=выход&tabnum=069017785-test"
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
                        document.location.reload(true);
                    },
                    error: function (msg) {
                        alert('Ошибка admin');
                    }
                });
		alert("Данные сгенерированы!");
		

	});

</script>
@endsection
