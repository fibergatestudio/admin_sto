<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    

@endsection

@section('content')


<h3>Информация о машине:</h3>

{{-- Статическая информация по наряду --}}
<div class="row">
  <div class="list-group col-md-6">
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">Название:</h5>
      </div>
      <h4 class="mb-1 text-success">{{ $car_info->general_name }}</h4>
    </a>
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">Рег номер:</h5>
      </div>
      <h4 class="mb-1 text-success">{{ $car_info->reg_number }}</h4>
    </a>
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Тип топлива:</h5>
        </div>
        <h4 class="mb-1 text-success">{{ $car_info->fuel_type }}</h4>
    </a>
  </div>
  <div class="list-group col-md-6">
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">VIN номер:</h5>
      </div>
      <h4 class="mb-1 text-success">{{ $car_info->vin_number }}</h4>
    </a>
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">Обьем двигателя:</h5>
      </div>
      <h4 class="mb-1 text-success">{{ $car_info->engine_capacity }}</h4>
    </a>
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">Пробег:</h5>
      </div>
      <h4 class="mb-1 text-success">{{ $car_info->mileage_km }}</h4>
    </a>
  </div>
</div>
<br>


<div class="card-p card">
    <h2>Активные Наряды</h2>
    <table id="table" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th scope="col">№</th>
            <th scope="col">Дата</th>
            <th scope="col">Модель\Марка</th>
            <th scope="col">Год</th>
            <th scope="col">Рег номер</th>
            <th scope="col">VIN</th>
            <th scope="col">Цвет</th>
            <th scope="col">Рабочие зоны</th>
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

                {{-- Название машины --}}
                <td>{{ $assignment->car_name }}</td>

                {{-- Год --}}
                <td>{{ $assignment->release_year }}</td>

                {{-- Рег номер --}}
                <td>{{ $assignment->reg_number }}</td>

                {{-- VIN --}}
                <td>{{ $assignment->vin_number }}</td>

                {{-- Цвет --}}
                <td>@if (!empty($assignment->car_color ))
                        <i style="width:35px; height:35px; display:flex;background-color:{{ $assignment->car_color }};"></i>
                    @endif
                </td>

                {{-- Рабочии зоны --}}
                <td style="color: white;">
                    <?php $workzone = $assignment->workzone; ?>
                    @if ($workzone)
                        @for($i=0; $i < count($workzone); $i++)
                            @foreach($workzone_data as $work_data)
                                @if ($work_data->id == $workzone[$i])
                                    <p style="background-color: {{ $work_data->workzone_color }};">{{ $work_data->general_name }}</p>
                                @endif
                            @endforeach
                        @endfor
                    @endif
                </td>

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
</div>


    <p>* Добавление наряда будет осуществляться из карточки машины (вкладка "Клиенты" или "Машины в сервисе")</p>
    <p>* Если не назначены рабочие зоны, наряд в списке нарядов отображаться не будет, а только на "Страница машины"</p>

<!-- Скрипты для таблиц -->
<script type="text/javascript">
 $(function () {
    $("#table_all").DataTable({
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      }
   });
   $("#table").DataTable({
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      }
   });

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
       url: "{{ url('/admin/assignments/assignments_index') }}",
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