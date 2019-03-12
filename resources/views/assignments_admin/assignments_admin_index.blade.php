<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница нарядов 
    <a href="{{ url('admin/add_client') }}">
        <div class="btn btn-success">
            Добавить наряд
        </div>
    </a>
@endsection

@section('content')

    <table id="table" class="table">
        <thead>
            <tr>
            <th>#</th>
            <th scope="col">№</th>
            <th scope="col">Дата создания</th>
            <th scope="col">Название</th>
            <th scope="col">Ответственный сотрудник</th>
            <th scope="col">Авто</th>
            <th scope="col">VIN</th>
            <th scope="col">Телефон</th>
            <th scope="col">ФИО</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="tablecontents">
        @foreach($assignments as $assignment)
            <tr class="row1" data-id="{{ $assignment->id }}">
                <td>
                    <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                    <i class="icon-menu-open"></i>
                    <i class=""></i>
                    </div>
                </td>
                {{-- Номер Наряда --}}
                <td>{{ $assignment->id }}</td>
                {{-- Дата --}}
                <td>{{ beautify_date($assignment->date_of_creation) }}</td>
                
                {{-- Название наряда --}}
                <td>{{ $assignment->description }}</td>

                {{-- Ответственный сотрудник --}}
                <td>{{ $assignment->employee_name }}</td>
                
                {{-- Машина --}}
                <td>{{ $assignment->car_name }}</td>

                {{-- VIN --}}
                <td>{{ $assignment->vin_number }}</td>

                {{-- Телефон --}}
                <td>{{ $assignment->clients_phone }}</td>

                {{-- ФИО --}}
                <td>{{ $assignment->clients_fio }}</td>

                {{-- Кнопка подробнее --}}
                <td>
                    <a href="{{ url('/admin/assignments/view/'.$assignment->id) }}">
                        <div class="btn btn-secondary">
                            Подробнее
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    
    * Добавление наряда будет осуществляться из карточки машины (вкладка "Клиенты" или "Машины в сервисе")


<!-- Скрипты для таблиц -->
<script type="text/javascript">
 $(function () {
   $("#table").DataTable();

   $( "#tablecontents" ).sortable({
     items: "tr",
     cursor: 'move',
     opacity: 0.6,
     update: function() {
         sendOrderToServer();
     }
   });

   function sendOrderToServer() {

     var order = [];
     $('tr.row1').each(function(index,element) {
       order.push({
         id: $(this).attr('data-id'),
         position: index+1
       });
     });

     $.ajax({
       type: "POST", 
       dataType: "json", 
       url: "{{ url('/admin/assignments_index') }}",
       data: {
         order:order,
         _token: '{{csrf_token()}}'
       },
       success: function(response) {
           if (response.status == "success") {
             console.log(response);
           } else {
             console.log(response);
           }
       }
     });

   }
 });

</script>
@endsection