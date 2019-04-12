@extends('layouts.limitless')

@section('page_name')
    Страница Записей
@endsection

@section('content')        

{{-- Таблица-вывод данных записей --}}
<table class="table">
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

{{-- Форма добавления записи --}}
<form action="{{ url('/add_record') }}" method="POST">
    @csrf
<div class="content py-5  bg-light">
    <div class="container">
        <div class="row">
                <div class="col-md-8 offset-md-2">
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
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Услуги</label>
                                <div class="col-lg-9">
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="record_services[]" type="checkbox" id="inlineCheckbox1" value="Полировка">
                                        <label class="custom-control-label" for="inlineCheckbox1">Полировка</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="record_services[]" type="checkbox" id="inlineCheckbox2" value="Развал-схождение">
                                        <label class="custom-control-label" for="inlineCheckbox2">Развал-схождение</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-checkbox">
                                        <input class="custom-control-input" name="record_services[]" type="checkbox" id="inlineCheckbox3" value="Шиномонтаж">
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
                                                    <input class="custom-control-input" name="record_services[]" type="checkbox" id="inlineCheckbox4" value="Химчистка">
                                                    <label class="custom-control-label" for="inlineCheckbox4">Химчистка</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Дата записи</label>
                                <div class="col-lg-5">
                                    <input class="form-control" name="record_date" type="date"  value="2019-02-12" min="2019-01-01" max="2019-12-31">
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Время</label>
                                <div class="col-lg-3" style="display: flex;">
                                    <input class="form-control" name="record_time" type="time" required>
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
    </div>
</div>
</form>

@endsection

@section('custom_scripts')

{{-- Модель + марка --}}

{{-- Скрипт автоматического пересчёта пробега километры-мили --}}
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