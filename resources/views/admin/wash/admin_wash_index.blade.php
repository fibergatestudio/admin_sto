@extends('layouts.limitless')

@section('page_name')
    Учёт мойки
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">
        <form action="{{ url('/admin/wash_post') }}" method="POST">
        @csrf
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
                        <input type="number" name="car_number" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Номер прицепа</label>
                        <input type="number" name="trailer_number" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Фирма</label>
                        <input type="text" name="firm_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-row col-md-12">  
                    <div class="form-group col-md-3">
                        <label>Метод оплаты</label>
                        <select class="form-control" name="payment_method" required>
                            <option>--Выберите метод оплаты--</option>
                            <option>Наличный</option>
                            <option>Безналичный</option>
                            <option>Терминал</option>
                        </select>
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
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Наружка без сушки" class="custom-control-input" id="check3" v-model="fine">
                            <label class="custom-control-label" for="check3">Наружка без сушки</label>
                        </div>
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
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Нет талона" class="custom-control-input" id="check6" v-model="fine">
                            <label class="custom-control-label" for="check6">Нет талона</label>
                        </div>
                    </div>
                    <div class="form-group col-md-1">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="wash_services[]" value="Терминал" class="custom-control-input" id="check7" v-model="fine">
                            <label class="custom-control-label" for="check7">Терминал</label>
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
            
            <!-- <div class="row">
                <div class="col">
                    <div class="custom-control">
                        <label>Фильтры типа: </label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" class="custom-control-input" id="check1" v-model="income">
                        <label class="custom-control-label" for="check1">Начисления</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="2" class="custom-control-input" id="check2" v-model="payout">
                        <label class="custom-control-label" for="check2">Выплаты</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="3" class="custom-control-input" id="check3" v-model="fine">
                        <label class="custom-control-label" for="check3">Штрафы</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="4" class="custom-control-input" id="check4" v-model="coffee">
                        <label class="custom-control-label" for="check4">Кофе</label>
                    </div>
                </div>
                <div class="col">
                    <div class="btn btn-info">Сорт.Старые</div>
                </div>
            </div> -->
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
            </tbody>
            </table>
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