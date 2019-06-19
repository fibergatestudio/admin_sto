@extends('layouts.limitless')

@section('page_name')
Финансы
@endsection


@section('content')

<style type="text/css">
.content { padding: 0; }
#table-calculation-profitability { padding: 1.25rem 1.25rem; }
.tabs-menu { width: 100%; padding: 0px; margin: 0 auto; }
.tabs-menu>input { display:none; }
.tabs-menu>div {
    display: none;
    padding: 12px;
    border: 1px solid #C0C0C0;    
}
.tabs-menu>label {
    display: inline-block;
    padding: 7px;
    margin: 0 -5px -1px 0;
    text-align: center;
    color: #666666;
    border: 1px solid #C0C0C0;
    background: #E0E0E0;
    cursor: pointer;
    width: 20.11%;
}
.tabs-menu>input:checked + label {
    color: #000000;
    border: 1px solid #C0C0C0;
    border-bottom: 1px solid #f2f2f2;
    background: #f2f2f2;
}
#tab_1:checked ~ #txt_1,
#tab_2:checked ~ #txt_2,
#tab_3:checked ~ #txt_3,
#tab_4:checked ~ #txt_4,
#tab_5:checked ~ #txt_5,
#tab_6:checked ~ #txt_6,
#tab_7:checked ~ #txt_7, 
#tab_8:checked ~ #txt_8,
#tab_9:checked ~ #txt_9,
#tab_10:checked ~ #txt_10{ display: block; }
</style>

    Оплата за смены, ожидает подтверждения<br>
    <table class="table">
        @foreach($pending_shifts as $shift)
            <tr>
                {{-- Дата --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Время открытия --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Время закрытия --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Сумма --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Кнопки контроля --}}
                <td>
                    {{-- Применить --}}
                    {{-- ... --}}

                    {{-- Изменить --}}
                    {{-- ... --}}

                    {{-- Кнопки "удалить" нету --}}
                    {{-- Вместо неё - изменить сумму на 0 --}}
                    {{-- Такая схема выбрана для того, чтобы сохранить целостность отчётности --}}

                </td>
            </tr>
        @endforeach
    </table>

    <!-- Вкладки -->
<div class="tabs-menu">
  

  <input type="radio" name="inset" value="" id="tab_1" checked>
  <label for="tab_1">Сервис</label>

  <input type="radio" name="inset" value="" id="tab_2">
  <label for="tab_2">Мойка</label>

  <input type="radio" name="inset" value="" id="tab_3">
  <label for="tab_3">Банк MD</label>

  <input type="radio" name="inset" value="" id="tab_4">
  <label for="tab_4">Польша MD</label>

  <input type="radio" name="inset" value="" id="tab_5">
  <label for="tab_5">Фирмы Кредиторы</label>

  <input type="radio" name="inset" value="" id="tab_6">
  <label for="tab_6">Фирмы Дебюторы</label>

  <input type="radio" name="inset" value="" id="tab_7">
  <label for="tab_7">Кредит</label>

  <input type="radio" name="inset" value="" id="tab_8">
  <label for="tab_8">Аукционы</label>

  <input type="radio" name="inset" value="" id="tab_9">
  <label for="tab_9">Инвестиции</label>

  <input type="radio" name="inset" value="" id="tab_10">
  <label for="tab_10">Без категории</label>

  <div id="txt_1">
  Сервис
  </div>
  <div id="txt_2">
  Мойка
  </div>
  <div id="txt_3">
  Банк MD
  </div>
  <div id="txt_4">
  Польша MD
  </div>
  <div id="txt_5">
  Фирмы Кредиторы
  </div>
  <div id="txt_6">
  Фирмы Дебюторы
  </div>
  <div id="txt_7">
  Кредит
  </div>
  <div id="txt_8">
  Аукционы
  </div>
  <div id="txt_9">
  Инвестиции
  </div>
  <div id="txt_10">
  Без категории
  </div>


</div>
@endsection