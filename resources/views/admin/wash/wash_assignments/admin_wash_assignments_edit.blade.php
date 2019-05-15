@extends('layouts.limitless')

@section('page_name')
    <div class="row">
        Наряд Мойка #{{ $id }}
        <div class="dropdown">
            <a href="{{ url()->previous() }}"><button class="btn btn-warning">Назад</button></a>
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Скачать
            <ul class="dropdown-menu">
                <li><a href="#">Заказ на наряд</a></li>
                <li><a href="#">Счет на оплату</a></li>
                <li><a href="#">Инвойс</a></li>
                <li><a href="#">Накладная</a></li>
            </ul>
        </div>
    </div>
@endsection

@section('content')


  <ul class="nav nav-tabs">
    <li class="btn btn-default"><a data-toggle="tab" href="#home" class="active show">Наряд</a></li>
    <li class="btn btn-default"><a data-toggle="tab" href="#menu1">Информация клиента</a></li>
    <li class="btn btn-default"><a data-toggle="tab" href="#menu2">Настройки печати</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active show">
        <div class="form-row">
            <div class="card card-outline-secondary col-md-12">
                <b class="text-center">Выполненная работа</b>
                <table id="table" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Дата</th>
                            <th>Марка</th>
                            <th>Рег.Номер</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($complete_work as $work)
                            <tr>
                                <td> {{ $work->id }} </td>
                                <td> <input class="form-control" value="{{ $work->date }}" disabled/></td>
                                <td> <input class="form-control" value="{{ $work->brand }}" disabled/></td>
                                <td> <input class="form-control" value="{{ $work->reg_number }}" disabled/></td>
                                <td> <input class="form-control" value="{{ $work->amount }}" disabled/></td>
                                <td> <input class="form-control" value="{{ $work->price }}" disabled/></td>
                                <td> <input class="form-control" value="{{ $work->sum }}" disabled/></td>
                                <td>
                                    <a href="{{ url('/admin/wash_assignments/id/delete_complete_work/'.$work->id) }}"><button class="btn btn-warning">X</button></a>

                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
                <form action="{{ url('/admin/wash_assignments/id/save_complete_work') }}" method="POST">
                @csrf 
                    <table id="table" class="table">
                        <tbody>
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead> 
                        <tr v-for="id in ids">
                                    <td>#</td>
                                    <input type="hidden" name="assignment_id" value="{{ $id }}">
                                    <td>   
                                        <input class="form-control" type="date" name="date" required></input>
                                    </td>
                                    <td>
                                        <input id="carBrand" class="form-control typeahead" type="text" name="brand" required></input> 
                                    </td>
                                    <td>  
                                        <input class="form-control" type="text" name="reg_number" placeholder="Рег.Номер" required></input>
                                    </td>
                                    <td>
                                        <input v-model="number1" class="form-control" name="amount" type="number"></input>
                                    </td>
                                    <td>    
                                        <input v-model="number2" class="form-control" name="price" type="number"></input>
                                    </td>
                                    <td>    
                                        <input class="form-control" name="sum" v-model="result"></input>
                                    </td>
                                    <td>    
                                        <button type="submit" class="btn btn-success">+</button>
                                    </td>
                        </tr>
                        <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Всего</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div id="menu1" class="tab-pane fade">
        <div class="content py-2 col-md-8 offset-md-2">
            <div class="container">
                <div class="form-row">
                    <div class="card card-outline-secondary col-md-12">
                        <form action="{{ url('/admin/wash_assignments/id/save_client_info') }}" method="POST">
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client_id }}">
                        <div class="form-group">
                            <div class="card-header">
                                <h3 class="mb-0">Информация клиента:</h3>
                            </div>
                            <hr>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Имя:</label>
                                    <input type="text" name="name" value="{{ $client_settings->name }}" class="form-control" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Телефон:</label>
                                    <input type="number" name="phone" value="{{ $client_settings->phone }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Название фирмы:</label>
                                    <input type="text" name="firm_name" value="{{ $client_settings->firm_name }}"class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Тип Клиента:</label>
                                    <select class="form-control" name="client_type" required>
                                    @if ($client_settings->client_type == 'Юр. лицо')
                                        <option>--Выберите--</option>
                                        <option>Физ. лицо</option>
                                        <option selected>Юр. лицо</option>
                                    @elseif ($client_settings->client_type == 'Физ. лицо')
                                        <option>--Выберите--</option>
                                        <option selected>Физ. лицо</option>
                                        <option>Юр. лицо</option>
                                    @else
                                        <option>--Выберите--</option>
                                        <option>Физ. лицо</option>
                                        <option>Юр. лицо</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Название банка:</label>
                                    <input type="text" name="bank_name" value="{{ $client_settings->bank_name }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Код банка:</label>
                                    <input type="text" name="bank_code" value="{{ $client_settings->bank_code }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Юридический адрес:</label>
                                    <input type="text" name="legal_address" value="{{ $client_settings->legal_address }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Банковский счет:</label>
                                    <input type="text" name="bank_account" value="{{ $client_settings->bank_account }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Фиксальный код:</label>
                                    <input type="text" name="fiscal_code" value="{{ $client_settings->fiscal_code }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Код НДС:</label>
                                    <input type="text" name="VAT_code" value="{{ $client_settings->VAT_code }}" class="form-control" required>
                                </div>
                            </div>
                            <!-- <div class="form-row col-md-12">
                                <label>Выбрать из списка:</label>
                                    <select class="form-control" required>
                                        <option>--Выберите--</option>
                                        <option>Тест</option>
                                    </select>
                            </div> -->
                            <hr>
                            <div class="form-row col-md-12">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="menu2" class="tab-pane fade">
        <div class="content py-2 col-md-8 offset-md-2">
            <div class="container">
                <div class="form-row">
                    <div class="card card-outline-secondary col-md-12">
                    <form action="{{ url('/admin/wash_assignments/id/save_print_settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="print_settings_id" value="{{ $print_settings_id }}">
                        <div class="form-group">
                            <div class="card-header">
                                <h3 class="mb-0">Настройки печати:</h3>
                            </div>
                            <hr>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Официальный номер наряда</label>
                                    <input type="text" class="form-control" name="assignment_number" value="{{ $print_settings->assignment_number }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Тип документа</label>
                                    <select class="form-control" name="doc_type"  required>
                                    @if ($print_settings->doc_type == 'Счет на оплату')
                                        <option>--Выберите тип документа--</option>
                                        <option selected>Счет на оплату</option>
                                        <option>Заказ-Наряд</option>
                                    @elseif ($print_settings->doc_type == 'Заказ-Наряд')
                                        <option>--Выберите тип документа--</option>
                                        <option>Счет на оплату</option>
                                        <option selected>Заказ-Наряд</option>
                                    @else
                                        <option>--Выберите тип документа--</option>
                                        <option>Счет на оплату</option>
                                        <option>Заказ-Наряд</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Дата документа:</label>
                                    <input type="date" class="form-control" value="{{ $print_settings->doc_date }}" name="doc_date" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Документ для</label>
                                    <select class="form-control" name="doc_for" required>
                                    @if ($print_settings->doc_for == 'Физ. лицо')
                                        <option>--Выберите--</option>
                                        <option selected>Физ. лицо</option>
                                        <option>Юр. лицо</option>
                                    @elseif ($print_settings->doc_for == 'Юр. лицо')
                                        <option>--Выберите--</option>
                                        <option>Физ. лицо</option>
                                        <option selected>Юр. лицо</option>
                                    @else
                                        <option>--Выберите--</option>
                                        <option>Физ. лицо</option>
                                        <option>Юр. лицо</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>НДС</label>
                                    <select class="form-control" name="VAT" required>
                                    @if ($print_settings->VAT == 'Добавить НДС')
                                        <option>--Выберите тип НДС--</option>
                                        <option selected>Добавить НДС</option>
                                        <option>Без НДС</option>
                                        <option>Цена с НДС</option>
                                    @elseif ($print_settings->VAT == 'Без НДС')
                                        <option>--Выберите тип НДС--</option>
                                        <option>Добавить НДС</option>
                                        <option selected>Без НДС</option>
                                        <option>Цена с НДС</option>
                                    @elseif ($print_settings->VAT == 'Цена с НДС')
                                        <option>--Выберите тип НДС--</option>
                                        <option>Добавить НДС</option>
                                        <option>Без НДС</option>
                                        <option selected>Цена с НДС</option>
                                    @else
                                        <option>--Выберите тип НДС--</option>
                                        <option>Добавить НДС</option>
                                        <option>Без НДС</option>
                                        <option>Цена с НДС</option>
                                    @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Показать дату</label>
                                    <select class="form-control" name="show_wash_date" required>
                                    @if ($print_settings->show_wash_date == 'Да')
                                        <option>--Выберите--</option>
                                        <option selected>Да</option>
                                        <option>Нет</option>
                                    @elseif ($print_settings->show_wash_date == 'Нет')
                                        <option>--Выберите--</option>
                                        <option>Да</option>
                                        <option selected>Нет</option>
                                    @else
                                        <option>--Выберите--</option>
                                        <option>Да</option>
                                        <option>Нет</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Заметка</label>
                                    <input type="text" class="form-control" name="note" value="{{ $print_settings->note }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Лого для накладной</label>
                                    <select class="form-control" name="show_logo" required>
                                    @if ($print_settings->show_logo == 'Да')
                                        <option>--Выберите--</option>
                                        <option selected>Да</option>
                                        <option>Нет</option>
                                    @elseif ($print_settings->show_logo == 'Нет')
                                        <option>--Выберите--</option>
                                        <option>Да</option>
                                        <option selected>Нет</option>
                                    @else
                                        <option>--Выберите--</option>
                                        <option>Да</option>
                                        <option>Нет</option>
                                    @endif

                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Накладная</label>
                                    <input type="text" class="form-control" name="invoice" value="{{ $print_settings->invoice }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Статус накладной</label>
                                    <select class="form-control" name="invoice_status" required>
                                    @if ($print_settings->invoice_status == 'Выпущено')
                                        <option>--Выберите--</option>
                                        <option selected>Выпущено</option>
                                        <option>Завершён</option>
                                        <option>Отменён</option>
                                    @elseif ($print_settings->invoice_status == 'Завершён')
                                        <option>--Выберите--</option>
                                        <option>Выпущено</option>
                                        <option selected>Завершён</option>
                                        <option>Отменён</option>
                                    @elseif ($print_settings->invoice_status == 'Отменён')
                                        <option>--Выберите--</option>
                                        <option>Выпущено</option>
                                        <option>Завершён</option>
                                        <option selected>Отменён</option>
                                    @else
                                        <option>--Выберите--</option>
                                        <option>Выпущено</option>
                                        <option>Завершён</option>
                                        <option>Отменён</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row col-md-12">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <script>
        var currentCounter = 1;

        var app1 = new Vue({
            el: '#home',
            data: {
                number1: 0,
                number2: 0,
                ids: [
                    { id: currentCounter},
                ]
            },
            computed: {
                result() {
                    return parseInt(this.number1) * parseInt(this.number2);
                    }
                },
            methods: {
                addNewEntry: function(){
                    currentCounter = currentCounter + 1;
                    this.ids.push({id: currentCounter});
                    document.getElementById("counter").value = currentCounter;
                },
            }
        });
    </script>

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