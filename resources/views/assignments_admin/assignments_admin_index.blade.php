<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница нарядов
    <!-- <a href="{{ url('admin/add_client') }}">
        <div class="btn btn-success">
            Добавить наряд
        </div>
    </a> -->


  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAssignment">
  Добавить наряд
  </button>
  <a href="{{ url('/admin/assignments/archive') }}" class="btn btn-danger mx-2">Архив</a>

        <div style="margin:0;"class="modal fade modal-dialog" id="addAssignment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header m-auto">
                        <h5 class="modal-title mx-2" id="exampleModalLabel">Добавление наряда</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    <div class="row">
                      <div class="col-lg-6">
                        <a href="{{ url('admin/clients/add_client') }}">
                            <div class="btn btn-large btn-primary">
                                Новый клиент
                            </div>
                        </a>
                      </div>
                      <div class="col-lg-6">
                        <a href="{{ url('admin/cars_in_service/add') }}">
                            <div class="btn btn-large btn-info">
                                Существующий
                            </div>
                        </a>
                      </div>
                    </div>

                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}



@endsection

@section('content')
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

<div class="card-p card">
    <h2>Все Наряды</h2>
    <table id="table_all" class="table">
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
        @foreach($assignments_list as $assignment)
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
