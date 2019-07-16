<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    
    Архив нарядов

@endsection

@section('content')

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
              <!-- <th scope="col"></th> -->
            </tr>
        </thead>
        <tbody id="tablecontents">
        @if(isset($assignments))
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

                {{-- Рабочиe зоны --}}
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
<!--                 <td>
    <a href="{{ url('/admin/assignments/view/'.$assignment->id) }}">
        <div class="btn btn-secondary">
            Подробнее
        </div>
    </a>
</td> -->
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
    <hr>

<!-- Скрипты для таблиц -->
<script type="text/javascript">
 $(function () {
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
