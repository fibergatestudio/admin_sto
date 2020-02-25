@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')        

{{-- Таблица-вывод данных записей --}}
<div class="card mx-2">
    <table class="table table-expense">
        <thead>
        <tr>
            <th>Статус</th>
            <th>Имя</th>
            <th>Год Авто</th>
            <th>Марка Авто</th>
            <th>Модель Авто</th>
            <th>Номер машины</th>
            <th>Дата записи</th>
            <th>Телефон</th>
            <th>Желаемое время</th>
            <th>Подтвержденное время</th>
            <th></th>{{-- Кнопки управления --}}
            <th></th>{{-- Кнопка удалить --}}
            <th></th>{{-- Кнопка редактировать --}}
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td>
                    @if($record->status == 'confirmed')
                        <b class="badge bg-success" type="text">{{ $record->status }}</b><br>
                    @else
                        <b class="badge bg-warning" type="text">{{ $record->status }}</b><br>
                    @endif
                </td>
                <td>
                    {{ $record->name }}<br>
                </td>
                <td>
                    {{ $record->car_year }}<br>
                </td>
                <td>
                    {{ $record->car_brand }}<br>
                </td>
                <td>
                    {{ $record->car_model }}<br>
                </td>
                <td>
                    {{ $record->car_number }}<br>
                </td>
                <td>
                    {{ $record->record_date }}<br>
                </td>
                <td>
                    {{ $record->phone }}<br>
                </td>
                <td>
                    {{ $record->record_time }}<br>
                </td>
                <td>
                    @if($record->status == 'unconfirmed')
                        <div class="row">
                            <div class="col-md-9">
                                <form action="{{ url('/complete_record/'.$record->id) }}" method="POST">
                                    @csrf
                                    <div style="display: flex;">
                                        <select style="width:auto;" class="dropdown form-control" style='width:60px;' name="confirmed_time" onchange='return timeSchedvalue(this.value)'>
                                            @foreach($record->available_time as $time_option)
                                                <option value="{{ $time_option }}">{{ $time_option }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="record_id" value="{{ $record->id }}">
                                        <input type="submit" value="Подтвердить" class="btn btn-primary"/>
                                    </div>
                                </form>
                            </div>
                            @else
                                {{ $record->confirmed_time }}
                            @endif
                        </div>
                </td>
                <td>
                    <div class="col-md-3">
                        <form action="{{ url('/delete_record/'.$record->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="record_id" value="{{ $record->id }}">
                            <input type="submit" value="Удалить" class="btn btn-warning"/>
                        </form>
                    </div>
                </td>
                <td>
                    <a href="{{ url('/records/'.$record->id.'/edit_page') }}"><button type="submit" class="btn btn-primary">Редактировать</button></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


{{-- Форма добавления записи --}}
<form action="{{ url('/add_record') }}" method="POST">
    @csrf
<div class="content py-3">
{{--    <div class="container">--}}
        <div class="row custom-card">
                <div class="w-100">
                <span class="anchor" id="formUserEdit"></span>
                <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3 class="mb-0">Форма Записи (тест)</h3>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" autocomplete="off">

                        <input type="hidden" name="status" value="unconfirmed">

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Имя</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="name" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Год Авто</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_year" type="number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Марка Авто</label>
                                <div class="col-lg-9">
                                    <input id="carBrand"  class="form-control typeahead" name="car_brand" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Модель Авто</label>
                                <div class="col-lg-9">
                                    <select id="carModel" class="form-control" name="car_model" type="text">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Номер машины</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_number" type="text">
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Услуги</label>
                                <div class="col-lg-9">
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="" type="checkbox" id="inlineCheckbox1" value="Полировка">
                                        <label class="custom-control-label" for="inlineCheckbox1">Полировка</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="" type="checkbox" id="inlineCheckbox2" value="Развал-схождение">
                                        <label class="custom-control-label" for="inlineCheckbox2">Развал-схождение</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="" type="checkbox" id="inlineCheckbox3" value="Шиномонтаж">
                                        <label class="custom-control-label" for="inlineCheckbox3">Шиномонтаж</label>
                                    </div>
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Показать больше
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                        {{-- Услуги - доп список --}}
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                <div class="form-check form-check-inline custom-checkbox">
                                                    <input class="custom-control-input" name="record_services" type="checkbox" id="inlineCheckbox4" value="Химчистка">
                                                    <label class="custom-control-label" for="inlineCheckbox4">Химчистка</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Услуги(new)</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="record_services" type="number">
                                    @foreach ($services as $service)
                                        <option value="{{ $service->service }}">{{ $service->service }}</option>
                                    @endforeach
                                        <option value="Полировка">Полировка</option>
                                        <option value="Развал-схождение">Развал-схождение</option>
                                        <option value="Шиномонтаж">Шиномонтаж</option>
                                        <option value="Химчистка">Химчистка</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addServiceModal">+</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Дата записи от</label>
                                <div class="col-lg-5">
                                    <input class="form-control" name="start" type="date"  value="2019-02-12" min="2019-01-01" max="2019-12-31">
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Время</label>
                                <div class="col-lg-3" style="display: flex;">
                                    <input class="form-control" name="record_time_from" type="time" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Дата записи до</label>
                                <div class="col-lg-5">
                                    <input class="form-control" name="end" type="date"  value="2019-02-12" min="2019-01-01" max="2019-12-31">
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Время</label>
                                <div class="col-lg-3" style="display: flex;">
                                    <input class="form-control" name="record_time_to" type="time" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Рабочий</label>
                                <div class="col-lg-9">
                                    <select class="form-control" name="employee" type="number">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Телефон</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="phone" type="number">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
{{--    </div>--}}
</div>
</form>

<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addServiceModal">
        Добавить заход денег
    </button> -->

    {{-- Добавить заход денег : Форма и модальное окно --}}
    <form action="{{ url('/records/add_service') }}" method="POST">
        @csrf

        <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить услугу</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                            {{-- Основание --}}
                            <div class="form-group">
                                <label>Название услуги</label>
                                <input type="text" name="service_name" class="form-control" required>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

<!--Календарь -->

<style>

.fc-sun { background-color:lightgray;  }
.fc-event-container { background-color:lightgreen; }

</style>

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

<div style="display: table-footer-group;" class="col-lg-7 bg-white card card-outline-secondary p-3">
    <div id='calendar'></div>
</div>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        // page is now ready, initialize the calendar...
        $('#calendar').fullCalendar({
            firstDay: 1,
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','οюнь','οюль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
            dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
            dayNamesShort: ["ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ"],
            defaultView: 'month',
            buttonText: {
                    prev: "Пред. месяц",
                    next: "След. месяц",
                    prevYear: "&nbsp;&lt;&lt;&nbsp;",
                    nextYear: "&nbsp;&gt;&gt;&nbsp;",
                    today: "Сегодня",
                    month: "Месяц",
                    week: "Неделя",
                    day: "День"
                },
            displayEventTime: false,
            events : [
                @foreach($calendar as $task)
                {
                    title : '{{ $task->description }}',
                    start : '{{ $task->start }}',
                    end : '{{ $task->end }}',
                    //url : '{{ url('/employee/calendar_edit', $task->id) }}'
                },
                @endforeach
            ]
        })
    });
</script>

@endsection

@section('custom_scripts')

{{-- Модель + марка --}}

<script>

{{-- Подтягиваем список моделей по бренду --}}

    {{-- При изменении значения бренда --}}
    $("#carBrand").change(function(){
        {{-- Получаем название бренда и подтягиваем по API список моделей --}}
        var brandName = $("#carBrand").val();
        console.log(brandName);
        var urlToFetch = "{{ url('admin/cars_in_service/api_models/') }}"+"/"+brandName;
        console.log(urlToFetch)
        $.get(urlToFetch, function(data){
            {{-- При успехе --}}
            console.log('success');
            //console.log(data);
            {{-- Очищаем select --}}
            $("#carModel").empty();
            {{-- Подставляем новые модели--}}
            var modelsArray = JSON.parse(data);
            console.log(modelsArray);
            for(var modelIteration in modelsArray){
                console.log(modelsArray[modelIteration]);
                $("#carModel").append('<option value="'+modelsArray[modelIteration]+'">'+modelsArray[modelIteration]+'</option>');
            }
        }
        
        );{{-- /.get --}}
    
    }); {{-- /carBrand.change--}}

{{-- Конец работы с моделями--}}

{{-- Typeahead --}}

console.log('ok');
var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

$.get( "{{ url ('admin/cars_in_service/api_brands') }} ", function(data) {
  
  var states = JSON.parse(data);
  console.log(states);

  $('.typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  source: substringMatcher(states)
});

})
  .done(function() {
    //alert( "second success" );
  })
  .fail(function() {
    alert( "error" );
  })
  .always(function() {
    //alert( "finished" );
  });



</script>
@endsection