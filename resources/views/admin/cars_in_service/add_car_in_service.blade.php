@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    {{-- Если клиент задан изначально--}}
    @if(!empty($client))
        <h2>Добавить машину клиента: {{ $client->general_name }}</h2>

        {{-- Форма добавления машины для клиента --}}
        <form action="{{ url('admin/cars_in_service/add') }}" method="POST">
            @csrf
            {{-- ID клиента --}}
            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="form-group">
                <label>Название</label>
                <input class="form-control" type="text" name="car_general_name">
            </div>
            
            <button type="submit" class="btn btn-primary">
                Сохранить
            </button>
        </form>
        <hr>
        {{-- Вернуться в карточку клиента --}}
        <a href="{{ url('/admin/view_client/'.$client->id) }}">
            <div class="btn btn-secondary">
                Вернуться
            </div>
        </a>
    
    @else
        {{-- Если клиент НЕ задан --}}
        {{-- и если база клиентов не пустая--}}
        @if(!empty($clients))

        {{-- Форма добавления машины с выбором клиента --}}
        <h2>Форма добавления авто</h2>

        <form action="{{ url('admin/cars_in_service/add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
                {{-- Выбор клиента --}}
                <div class="form-group">
                    <label>Выберите клиента</label>
                    <select class="form-control" name="client_id">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->general_name }}</option>
                        @endforeach

                    </select>
                </div>

                <p>Если вашего клиента нет в списке, пожалуйста, добавьте сначала нового клиента. Это можно сделать, нажав на вот эту кнопку:
                    
                </p>
                {{-- Добавить клиента : переход на страницу --}}
                <a href="{{ url('admin/add_client') }}">
                    <div class="btn btn-secondary">
                        Добавить клиента
                    </div>
                </a>
                <hr>
                
                {{-- Прикрепить документ --}}

                <div class="form-group">
                    <label>Прикрепить документ</label>
                    <input type="file" name="document">
                </div>

                {{-- Название машины --}}
                <div class="form-group">
                    <label>Название машины</label>
                    <input type="text" name="car_general_name" class="form-control">
                </div>

                {{-- Модель и марка --}}
                {{-- Марка : от неё будут подтягивать подсказки . Используется Typeahead --}}
                <div class="form-group">
                    <label>Марка машины</label>
                    <input type="text" name="car_brand" id="carBrand" class="form-control typeahead">
                </div>

                {{-- Модель : подтягивается с базы --}}
                {{-- ... --}}
                <div class="form-group">
                    <label>Модель машины</label>
                    <select id="carModel" name="car_model" class="form-control">
                    </select>
                </div>
                

                {{-- Пробег --}}
                {{-- Километры --}}
                <div class="form-group">
                    {{-- ... --}}
                    <label>Пробег в километрах</label>
                    <input type="number" name="mileage_km" class="form-control" min="0" value="0" id="mileageKM" step="any">
                </div>
                
                
                {{-- Мили --}}
                <div class="form-group"> 
                    <label>Пробег в милях</label>
                    <input type="number" name="mileage_miles" class="form-control" min="0" value="0" id="mileageMiles" step="any">
                </div>

                {{-- Скрипт на автоматический пересчёт - внизу, в секции custom_scripts --}}

                {{-- Новые поля --}}
                {{-- Год выпуска --}}
                <div class="form-group">
                    <label>Год выпуска</label>
                    <input type="number" min="1900" max="2099" step="1" value="2019" name="release_year" id="releaseyear" class="form-control typeahead">
                </div>

                {{-- Регистрационный номер --}}
                <div class="form-group">
                    <label>Регистрационный номер</label>
                    <input type="number" name="reg_number" id="regnumber" class="form-control typeahead">
                </div>

                {{-- Тип топлива --}}
                <div class="form-group">
                    <label>Тип топлива</label>
                    <input type="text" name="fuel_type" id="fueltype" class="form-control typeahead">
                </div>

                {{-- VIN --}}
                <div class="form-group">
                    <label>VIN</label>
                    <input type="text" name="vin_number" id="vinnumber" class="form-control typeahead">
                </div>

                {{-- Обьем мотора --}}
                <div class="form-group">
                    <label>Объем мотора</label>
                    <input type="text" name="engine_capacity" id="enginecapacity" class="form-control typeahead">
                </div>
                
                


                <button type="submit" class="btn btn-success">
                    Добавить авто
                </button>
                
            </form>

        @else
        {{-- Если клиентов нет --}}
            <p>На данный момент в базе нет клиентов. Пожалуйста, добавьте сначала нового клиента. Это можно сделать, нажав на вот эту кнопку:</p>
               {{-- Добавить клиента : переход на страницу --}}
               <a href="{{ url('admin/add_client') }}">
                    <div class="btn btn-secondary">
                        Добавить клиента
                    </div>
                </a>
        @endif {{-- Конец условия проверки пустая ли база клиентов--}}

    @endif
    

    
@endsection

@section('custom_scripts')

{{-- Скрипт автоматического пересчёта пробега километры-мили --}}
<script>

{{-- Коэффициент 1 миля = Х километров --}}
var milesToKilometers = 1.609;

{{-- При вводе километров --}}
$("#mileageKM").change(function(){
    {{-- Получаем текущие километры --}}
    var currentKilometers = $("#mileageKM").val();
    {{-- Задаём мили --}}
    $("#mileageMiles").val(Math.round(currentKilometers/milesToKilometers));
});

{{-- При вводе миль --}}
$("#mileageMiles").change(function(){
    {{-- Получаем текущие мили --}}
    var currentMiles = $("#mileageMiles").val();
    //var currentMiles = $(Math.round("#mileageMiles").val());
    {{-- Задаём километры --}}
    $("#mileageKM").val(Math.round(currentMiles*milesToKilometers));
});


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