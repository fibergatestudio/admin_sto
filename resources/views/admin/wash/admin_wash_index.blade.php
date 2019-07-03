@extends('layouts.limitless')

@section('page_name')
    Учёт мойки
        <a href="{{ url('/admin/wash/report/'.$year.'/'.$month.'/'.$day) }}"><button class="btn btn-primary">Отчет</button></a>
        <a href="{{ url('/admin/wash/select_date') }}"><button class="btn btn-primary">Выбрать дату</button></a>
@endsection

@section('content')
<div class="form-row">
    <b>Текущая дата: {{ $year }} {{ $month }} {{ $day }}</b><br>
    <div class="card card-outline-secondary col-md-12">
        <form action="{{ url('/admin/wash_post') }}" method="POST">
        @csrf

            <input type="hidden" value="{{ $year }}" name="year">
            <input type="hidden" value="{{ $month }}" name="month">
            <input type="hidden" value="{{ $day }}" name="day">

            <div class="form-group">
                <div class="card-header">
                    <!-- <h3 class="mb-0">Учёт мойки:</h3> -->
                </div>
                <!-- <hr> -->
                <div class="form-row col-md-12">  
                    <div class="form-group col-md-3">
                        <label>Марка</label>
                        <input type="text" name="car_model" class="form-control typeahead" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Номер</label>
                        <input type="text" name="car_number" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Номер прицепа</label>
                        <input type="text" name="trailer_number" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Фирма</label>
                        <!-- <input type="text" name="firm_name" class="form-control" required> -->
                        <select class="form-control" name="firm_name" required>
                            <option>--Выберите Фирму--</option>
                        @foreach ($clients_list as $client)
                            <option>{{ $client->general_name }}</option>

                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row col-md-12">  
                    <div class="form-group col-md-3">
                        <label>Метод оплаты</label>
                        <div class="row">
                            <div class="p-1">
                                <input type="radio" id="huey" name="payment_method" value="Наличный"
                                        checked>
                                <label for="huey">Наличный</label>
                            </div>

                            <div class="p-1">
                                <input type="radio" id="dewey" name="payment_method" value="Безналичный">
                                <label for="dewey">Безналичный</label>
                            </div>

                            <div class="p-1">
                                <input type="radio" id="louie" name="payment_method" value="Терминал">
                                <label for="louie">Терминал</label>
                            </div>
                        </div>

                        <!-- <select class="form-control" name="payment_method" required>
                            <option>--Выберите метод оплаты--</option>
                            <option>Наличный</option>
                            <option>Безналичный</option>
                            <option>Терминал</option>
                        </select> -->
                    </div>

                    <div class="form-group col-md-1">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Наружка" class="custom-control-input" id="check1" v-model="fine">
                            <label class="custom-control-label" for="check1">Наружка</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Внутрянка" class="custom-control-input" id="check2" v-model="fine">
                            <label class="custom-control-label" for="check2">Внутрянка</label>
                        </div>
                        <!-- <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Наружка без сушки" class="custom-control-input" id="check3" v-model="fine">
                            <label class="custom-control-label" for="check3">Наружка без сушки</label>
                        </div> -->
                    </div>
                    <div class="form-group col-md-1">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Мотор" class="custom-control-input" id="check4" v-model="fine">
                            <label class="custom-control-label" for="check4">Мотор</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Радиатор" class="custom-control-input" id="check5" v-model="fine">
                            <label class="custom-control-label" for="check5">Радиатор</label>
                        </div>
                        <!-- <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Нет талона" class="custom-control-input" id="check6" v-model="fine">
                            <label class="custom-control-label" for="check6">Нет талона</label>
                        </div> -->
                    </div>
                    <div class="form-group col-md-1">
                        <!-- <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Терминал" class="custom-control-input" id="check7" v-model="fine">
                            <label class="custom-control-label" for="check7">Терминал</label>
                        </div> -->
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Нет талона" class="custom-control-input" id="check6" v-model="fine">
                            <label class="custom-control-label" for="check6">Нет талона</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Наружка без сушки" class="custom-control-input" id="check3" v-model="fine">
                            <label class="custom-control-label" for="check3">Наружка без сушки</label>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Сумма</label>
                        <input type="text" name="payment_sum" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Бокс</label>
                        <select name="box_number" class="form-control" required>
                            <option>--Выберите номер бокса--</option>
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>
                </div>
            </form>
            <hr>
            <div class="form-row col-md-12">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </div>
    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Учёт мойки, Таблица:</h3>
            </div>
        </form>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Марка</th>
                        <th>Номер</th>
                        <th>Номер прицепа</th>
                        <th>Фирма</th>
                        <th>Метод оплаты</th>
                        <th>Чекбоксы</th>
                        <th>Сумма</th>
                        <th>Бокс</th>
                    </tr>
                </thead>
            <tbody >
                @foreach ($car_wash_table as $car_wash)
                    <tr>
                        <td>    
                           {{ $car_wash->id }}
                        </td>
                        <td>
                            {{ $car_wash->car_model }}
                        </td>
                        <td>
                            {{ $car_wash->car_number }}
                        </td>
                        <td>
                            {{ $car_wash->trailer_number }}
                        </td>
                        <td>
                            {{ $car_wash->firm_name }}
                        </td>
                        <td>
                            {{ $car_wash->payment_method }}
                        </td>
                        <td>
                            {{ $car_wash->wash_services }}
                        </td>
                        <td>
                            {{ $car_wash->payment_sum }}
                        </td>
                        <td>
                            {{ $car_wash->box_number }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th>Всего наличные</th>
                        <th>{{ $car_wash_sum_cash }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr> 
                    <tr>
                        <th>Всего безналичные</th>
                        <th>{{ $car_wash_sum_non_cash }}</th>
                    </tr> 
                    <tr>
                        <th>Всего терминал</th>
                        <th>{{ $car_wash_sum_terminal }}</th>
                    </tr> 
                    <tr>
                        <th>Всего</th>
                        <th>{{ $car_wash_sum_total }}</th>
                    </tr> 
            </tbody>
            </table>
        </div>
        <hr>
        <div class="form-group text-center">
            <a href="{{ url('/admin/wash/select_date/'.$year.'/'.$month.'/'.$day.'/close_cashbox') }}"><button class="btn btn-primary">Закрыть кассу</button></a>
        </div>
    </div>
    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Зачислить работникам</h3>
                <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Работник</th>
                        <th>Зарплата</th>
                        <th>На выкуп</th>
                        <th>Фирма</th>
                        <th>Выкуплено</th>
                        <th>Обед</th>
                    </tr>
                </thead>
                <tbody >
                @foreach ($wash_worker as $worker)
                    <tr>
                        <td>{{ $worker->general_name }}</td>
                        <td>{{ $worker->standard_shift_wage }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary">Зачислить</button>
            </div>
        </div>
    </div>
</div>

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