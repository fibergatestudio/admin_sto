@extends('layouts.limitless')

@section('page_name')
Наряд: {{ $assignment->description }}

@if($message = Session::get('success'))
   <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
           <strong>{{ $message }}</strong>
   </div>
@endif

<style type="text/css">
  #download_xls{
    display: none;
    position: absolute;
    right: 0;
    top: 70px;
    margin: 5px;
    width: 150px;
    z-index: 100;
  }
  button.download-xls{
    position: absolute;
    right: 0;
  }
</style>

<select class="form-control" id="download_xls">
  <option value="">Выбрать</option>
  <option value="work_order">Заказ-наряд</option>
  <option value="invoice_for_payment">Счет на оплату</option>
  <option value="invoice">Инвойс</option>
  <option value="tax_invoice">Накладная</option>
  <option value="inner_outfit">Внутренний наряд</option>
</select>
<button type="button" onclick="downloadXls()" class="btn btn-success download-xls" style="margin: 10px">Скачать</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form action="{{ url('/admin/assignments/change_name') }}" method="POST">
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Смена названия наряда</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          {{-- ID наряда --}}
          <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

          {{-- Новое название --}}
          <div class="form-group">
            <label>Новое название:</label>
            <input type="text" name="new_name" class="form-control" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary">Добавить</button>
        </div>
      </div>
    </div>
  </form>
</div>


<div style="margin-top: 10px">
  <button type="button" onclick="readMore()" class="btn" style="margin: 10px">Подробнее</button>
  <a href="{{ url('/admin/assignments/assignments_index') }}">
          <span class="btn btn-danger">
              Вернуться
          </span>
  </a>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal1" style="margin: 10px">
    Изменить название
  </button>
  <!-- Вызов попапа принятия аванса -->
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#prepaidModal1" style="margin: 10px">
      Принять аванс
  </button>

  <button type="button" class="btn btn-primary" onclick="calculationProfitability()" style="margin: 10px">Таблица рассчета рентабельности</button>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchModal1" style="margin: 10px">Поиск</button>
  <!-- Вызов попапа Добавление наряда -->
  <button type="button" class="btn btn-primary" style="margin: 10px" data-toggle="modal" data-target="#addAssignment">Добавить наряд</button>
  <button type="button" class="btn btn-primary" style="margin: 10px">Клиентский наряд</button>

</div>


<!-- Модальное окно принятия аванса -->
<div class="modal fade" id="prepaidModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ url('/admin/manage_assignment/add_income_entry') }}" method="POST">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="prepaidModalLabel">Форма принятия аванса</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        {{-- ID наряда --}}
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                        <div class="form-row">
                            {{-- Сумма --}}
                            <div class="form-group col-md-6">
                                <label>Сумма Аванса</label>
                                <input type="number" name="amount" min="0" class="form-control" required>
                            </div>
                            {{-- Валюта --}}
                            <div class="form-group col-md-6">
                                <label>Валюта</label>
                                <select name="currency"class="form-control">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </div>
                        </div>

                        {{-- Основание --}}
                        <div class="form-group">
                            <label>Основание (реквизиты документа или действие)</label>
                            <input type="text" name="basis" class="form-control" required>
                        </div>

                        {{-- Описание - не обязательно --}}
                        <div class="form-group">
                            <input type="hidden" name="description" class="form-control" value="Принятие Аванса">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Принять</button>
                    </div>
                </div>
            </div>
        </form>

</div>


<!-- Модальное окно  Добавление наряда-->
<div style="margin:0;"class="modal fade modal-dialog" id="addAssignment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <div class="m-auto">
                    <h5 class="ml-5 pl-5 text-center modal-title" id="exampleModalLabel">Добавление наряда</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">

            <div class="row">
              <div class="col-lg-6">
                <a href="{{ url('admin/clients/add_client') }}">
                    <div class="btn btn-large btn-primary">
                        Новый клиент
                    </div>
                </a>
              </div>
              <div class="col-lg-6">
                <a href="{{ url('admin/cars_in_service/index') }}">
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

<style type="text/css">

label{
    font-weight: bold;
    /*text-align: left;*/
    display: block;
}
.form-row>.col, .form-row>[class*=col-] {
    text-align: left;
}
.content { padding: 0; }
#table-calculation-profitability { padding: 1.25rem 1.25rem; }
.tabs-menu { width: 100%; padding: 0px; margin: 0 auto; }
.tabs-menu>input { display:none; }
.tabs-menu>div {
    display: none;
    padding: 12px;
    border: 1px solid #C0C0C0;
    text-align: center;
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
#tab_5:checked ~ #txt_5{ display: block; }

</style>

<div class="card">
    {{-- Статическая информация по наряду --}}
    <table id="read-more" class="w-100  m-3 table" style="display: none;">
        <thead>
        <tr>
            <th>Клиент</th>
            <th>Машина</th>
            <th>Год машины</th>
            <th>Гос. номер</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $assignment->client_name }}</td>
            <td>{{ $assignment->car_name }} </td>
            <td>{{ $assignment->car_year }} </td>
            <td>{{ $assignment->car_reg_number }} </td>
        </tr>
        </tbody>
    </table>
</div>



    {{-- Расчет рентабельности --}}
    <div id="table-calculation-profitability"  style="display: none;">
        <h2>Расчет рентабельности</h2>
        <div class="card card-p">
    {{-- Вывод списока расходов и доходов --}}
    <table class="table">
        <thead>
        <tr>
            <th>Сумма</th>
            <th>Основание</th>
            <th>Валюта</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sum = 0;
        $sum_income = 0;
        $sum_expense = 0;
        ?>
        @foreach($assignment_income as $income_entry)
            <?php
            if ($income_entry->currency === 'USD') {
                $sum += round(($income_entry->amount)/$usd);
                $sum_income += round(($income_entry->amount)/$usd);
            }
            else if ($income_entry->currency === 'EUR') {
                $sum += round(($income_entry->amount)/$eur);
                $sum_income += round(($income_entry->amount)/$eur);
            }
            else {
                $sum += $income_entry->amount;
                $sum_income += $income_entry->amount;
            }
            ?>
            <tr>
                <td>
                    {{ $income_entry->amount }}<br>
                </td>
                <td>
                    {{ $income_entry->basis }}<br>
                </td>
                <td>
                    {{ $income_entry->currency }}<br>
                </td>
            </tr>
        @endforeach
        @foreach($assignment_expense as $expense_entry)
            <?php
            if ($expense_entry->currency === 'USD') {
                $sum -= round(($expense_entry->amount)/$usd);
                $sum_expense += round(($expense_entry->amount)/$usd);
            }
            else if ($expense_entry->currency === 'EUR') {
                $sum -= round(($expense_entry->amount)/$eur);
                $sum_expense += round(($expense_entry->amount)/$eur);
            }
            else {
                $sum -= $expense_entry->amount;
                $sum_expense += $expense_entry->amount;
            }
            ?>
            <tr>
                <td>-
                    {{ $expense_entry->amount }}<br>
                </td>
                <td>
                    {{ $expense_entry->basis }}<br>
                </td>
                <td>
                    {{ $expense_entry->currency }}<br>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
            <hr/>
    <h4 id="total">Итого: {{ $sum }} лей</h4>
</div>
    </div>


<div class="tabs-menu">


  <input type="radio" name="inset" value="" id="tab_1" checked>
  <label for="tab_1">Наряд</label>

  <input type="radio" name="inset" value="" id="tab_2">
  <label for="tab_2">Информация автомобиля</label>

  <input type="radio" name="inset" value="" id="tab_3">
  <label for="tab_3">Информация клиента</label>

  <input type="radio" name="inset" value="" id="tab_4">
  <label for="tab_4">Доходная часть</label>

  <input type="radio" name="inset" value="" id="tab_5">
  <label for="tab_5">Настройки печати</label>

  <div id="txt_1">


    {{-- Добавить новый зональный наряд : Переход --}}

    <div class="form-group">

        <div class="card card-p">
            <h3>Новый зональный наряд</h3>
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control work_direction">
                        @foreach($work_directions as $direction)
                            <option value="{{ $direction->id }}">{{ $direction->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary work_direction">Добавить зональный наряд</button>
                </div>
            </div>
        </div>

    </div>

    <button class="btn btn-default" onclick="window.location.reload()"><b>Обновить страницу после изменения информации о расходах</b></button>

    <hr/>


    <!---------------------------------  Динамические таблицы зональных нарядов  //--------------------------------------------->
    <style type="text/css">
      table.table
      {
        /*max-width: 1000px;*/
        border: 1px solid #b7b7b7;        
      }
      form:nth-child(6) table.table{
        /*max-width: 770px;*/
      }
      table.table, table.table td 
      {
        vertical-align: top;
      }
      table.table td, table.table th
      {
        padding: 0;
        padding-top: 10px;
        border-left: 1px solid #b7b7b7;
      }
      table.table td
      {
        /*padding-left: 4px;*/
        padding: 4px;
        vertical-align: baseline;
      }
      table.table-income thead{
          background: #E0E0E0;
      }
      table.table-income td  .form-control{
          border-radius: 0;
          border: none;
      }

      table.table-income td {
          vertical-align: top;

      }
      table.table th{
        text-align: center;
        vertical-align: middle;
      }
      form:nth-child(2) table.table tr td:first-child, form:nth-child(2) table.table tr th:first-child
      {
        width: 20px;
      }
      form:nth-child(2) table.table tr td:nth-child(2), form:nth-child(2) table.table tr th:nth-child(2)
      {
        width: 170px;
      }
      form:nth-child(2) table.table tr td:nth-child(3), form:nth-child(2) table.table tr th:nth-child(3),
      form:nth-child(2) table.table tr td:nth-child(4), form:nth-child(2) table.table tr th:nth-child(4)
      {
        width: 80px;
      }
      form:nth-child(2) table.table tr td:nth-child(5), form:nth-child(2) table.table tr th:nth-child(5)
      {
        width: 170px;
      }
      form:nth-child(2) table.table tr td:nth-child(6), form:nth-child(2) table.table tr th:nth-child(6)
      {
        width: 170px;
      }
      form:nth-child(2) table.table tr td:nth-child(7), form:nth-child(2) table.table tr th:nth-child(7)
      {
        width: 20px;
      }
      form:nth-child(2) table.table tr td:nth-child(8), form:nth-child(2) table.table tr th:nth-child(8)
      {
        width: 70px;
      }
      form:nth-child(2) table.table tr td:nth-child(9), form:nth-child(2) table.table tr th:nth-child(9)
      {
        width: 60px;
      }
      form:nth-child(2) table.table tr td:nth-child(10), form:nth-child(2) table.table tr th:nth-child(10)
      {
        width: 30px;
      }
      form:nth-child(2) table.table tr td:nth-child(11), form:nth-child(2) table.table tr th:nth-child(11)
      {
        width: 95px;
      }
      form:nth-child(2) table.table tr td:nth-child(11), form:nth-child(2) table.table tr th:nth-child(11)
      {
        width: 95px;
      }
      form:nth-child(6) table.table tr td:nth-child(1), form:nth-child(6) table.table tr th:nth-child(1)
      {
        width: 210px;
      }
      form:nth-child(6) table.table tr td:nth-child(2), form:nth-child(6) table.table tr th:nth-child(2)
      {
        width: 175px;
      }
      form:nth-child(6) table.table tr td:nth-child(3), form:nth-child(6) table.table tr th:nth-child(3)
      {
        width: 70px;
      }
      form:nth-child(6) table.table tr td:nth-child(4), form:nth-child(6) table.table tr th:nth-child(4)
      {
        width: 30px;
      }
      form:nth-child(6) table.table tr td:nth-child(5), form:nth-child(6) table.table tr th:nth-child(5)
      {
        width: 70px;
      }
      form:nth-child(6) table.table tr td:nth-child(6), form:nth-child(6) table.table tr th:nth-child(6)
      {
        width: 65px;
      }
      form:nth-child(6) table.table tr td:nth-child(7), form:nth-child(6) table.table tr th:nth-child(7)
      {
        width: 50px;
      }
      form:nth-child(6) table.table tr td:nth-child(8), form:nth-child(6) table.table tr th:nth-child(8)
      {
        width: 95px;
      }
      table.table tr td:last-child
      {
        display: flex;
        height: 45px;
        justify-content: center;
      }
      table.table tr td:last-child button
      {
        height: 25px;
        align-self: center;
      }
    </style>


    <h1 id="dynamic-tables">Динамические таблицы зональных нарядов</h1>

    <!--  Dynamic-With-Data  //-->

    <?php //echo '<pre>'.print_r($new_sub_assignments_arr,true).'</pre>'; ?>

    @foreach($new_sub_assignments_arr as $sub_key => $sub_value)

    @if($sub_value[0]->count() != 0)

    @if($sub_value[0]->count() != 0)

    <?php
        $new_sub_work_assignments = $sub_value[0];
        $work_direction_name = '';
        $work_direction_id = 0;
        foreach ($work_directions as $value) {
          if ($value->id == $sub_key) {
            $work_direction_name = $value->name;
            $work_direction_id = $value->id;
          }
        }
    ?>

    <div class="dyn-tab dynamic-{{ $work_direction_id }}">

                  <?php
                        $j = 1;
                        $selected_work_id = 0;
                        $selected_employee_id = 0;
                        $total_work_sum = 0;
                        $thead_work = true;
                  ?>
                  @foreach($new_sub_work_assignments as $new_sub_assignment)

                  @if($thead_work)
                  <?php $thead_work = false; ?>

      <h3>Выполненная работа ({{ $work_direction_name }})</h3>
<div class="card card-p">
    <form method="post" action="">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Рабочая зона</th>
                <th scope="col">Время начала работ</th>
                <th scope="col">Время окончания работ</th>
                <th scope="col">Ответственный сотрудник</th>
                <th scope="col">Список выполненных работ</th>
                <th scope="col">Кол-во</th>
                <th scope="col">Цена</th>
                <th scope="col">Валюта</th>
                <th scope="col">Сумма</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody id="dynamic-{{ substr($new_sub_assignment->work_row_index, 0, 1) }}-1">
            @endif

            <tr style="background-color: {{ ($new_sub_assignment->work_is_locked == 'active') ? '#f0f0f0' : 'transparent' }} ;"  data-tr-row="{{ $new_sub_assignment->work_row_index }}">
                <td class="number_sub_assignment">
                    <strong><?=$j?></strong>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <select disabled onchange="workzoneSelect(this)" data-row="{{ $new_sub_assignment->work_row_index }}" name="d_table_workzone" style="max-width: 100px;">
                                @else
                                    <select onchange="workzoneSelect(this)" data-row="{{ $new_sub_assignment->work_row_index }}" name="d_table_workzone" style="max-width: 100px;">
                                        @endif
                                        <option value="">Выбрать</option>
                                        @foreach($workzone_data as $work_data)
                                            @if($selected_work_id == $work_data->id)
                                                <option selected value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                            @else
                                                <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <input disabled data-row="{{ $new_sub_assignment->work_row_index }}" onclick="dataTransmission(this)" type="date" style="max-width: 100px" name="d_table_time_start" value="{{ $new_sub_assignment->d_table_time_start }}">
                        @else
                            <input data-row="{{ $new_sub_assignment->work_row_index }}" onclick="dataTransmission(this)" type="date" style="max-width: 100px" name="d_table_time_start" value="{{ $new_sub_assignment->d_table_time_start }}">
                        @endif
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <input disabled data-row="{{ $new_sub_assignment->work_row_index }}" onclick="dataTransmission(this)" type="date" style="max-width: 100px" name="d_table_time_finish" value="{{ $new_sub_assignment->d_table_time_finish }}">
                        @else
                            <input data-row="{{ $new_sub_assignment->work_row_index }}" onclick="dataTransmission(this)" type="date" style="max-width: 100px" name="d_table_time_finish" value="{{ $new_sub_assignment->d_table_time_finish }}">
                        @endif
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <select disabled onchange="employeeSelect(this)" data-row="{{ $new_sub_assignment->work_row_index }}" name="d_table_responsible_officer" style="max-width: 100px;">
                                @else
                                    <select onchange="employeeSelect(this)" data-row="{{ $new_sub_assignment->work_row_index }}" name="d_table_responsible_officer" style="max-width: 100px;">
                                        @endif
                                        <option value="">Выбрать</option>
                                        @foreach($employees as $employee)
                                            @if($selected_employee_id == $employee->id)
                                                <option selected value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                            @else
                                                <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <input disabled data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="max-width: 75px" rows="1" cols="15" name="d_table_list_completed_works" value="{{ $new_sub_assignment->d_table_list_completed_works }}">
                        @else
                            <input data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="max-width: 75px" rows="1" cols="15" name="d_table_list_completed_works" value="{{ $new_sub_assignment->d_table_list_completed_works }}">
                        @endif
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <input disabled data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="{{ $new_sub_assignment->d_table_quantity }}">
                        @else
                            <input data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="{{ $new_sub_assignment->d_table_quantity }}">
                        @endif
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <input disabled data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="{{ $new_sub_assignment->d_table_price }}">
                        @else
                            <input data-row="{{ $new_sub_assignment->work_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="{{ $new_sub_assignment->d_table_price }}">
                        @endif
                    </label>
                </td>
                <td>
                    <label>
                        @if($new_sub_assignment->work_is_locked == 'active')
                            <select disabled data-row="{{ $new_sub_assignment->work_row_index }}" onchange="dataTransmission(this)" name="d_table_currency">
                                @else
                                    <select data-row="{{ $new_sub_assignment->work_row_index }}" onchange="dataTransmission(this)" name="d_table_currency">
                                        @endif
                                        @for($i=0; $i < count($currency_arr); $i++)
                                            @if($currency_arr[$i] == $new_sub_assignment->d_table_currency)
                                                <option selected value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                            @else
                                                <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                            @endif
                                        @endfor
                                    </select>
                    </label>
                </td>
                <td class="sum-row">
                    <strong data-row="{{ $new_sub_assignment->work_row_index }}">{{ $new_sub_assignment->work_sum_row }}</strong>
                </td>
                <td>
                    <button data-row="{{ $new_sub_assignment->work_row_index }}" type="button" class="add">+</button>
                    <button data-row="{{ $new_sub_assignment->work_row_index }}" type="button" class="del">-</button>
                    <button data-row="{{ $new_sub_assignment->work_row_index }}" type="button" onclick="buttonLock(this)" style="display: {{ ($new_sub_assignment->work_is_locked == 'active') ? 'block' : 'none' }} ;"><i class="icon-lock2"></i></button>
                    <button data-row="{{ $new_sub_assignment->work_row_index }}" type="button" onclick="buttonUnlocked(this)" style="display: {{ ($new_sub_assignment->work_is_locked == 'active') ? 'none' : 'block' }} ;"><i class="icon-unlocked2"></i></button>
                </td>
            </tr>
            <?php $j++; ?>
            <?php

            if ($new_sub_assignment->d_table_currency === 'USD') {
                $total_work_sum += round(($new_sub_assignment->work_sum_row)/$usd);
            }
            else if ($new_sub_assignment->d_table_currency === 'EUR') {
                $total_work_sum += round(($new_sub_assignment->work_sum_row)/$eur);
            }
            else {
                $total_work_sum += $new_sub_assignment->work_sum_row;
            }

            ?>
            @endforeach

            </tbody>
        </table>
    </form>
    <hr/>
    <h4>Итого: <?=$total_work_sum?> лей</h4>
</div>



      <hr/>
      @endif

      @if($sub_value[1]->count() != 0)

      <?php
          $new_sub_spares_assignments = $sub_value[1];
      ?>
                  <?php
                        $j = 1;
                        $total_spares_sum = 0;
                        $thead_spares = true;
                  ?>
                  @foreach($new_sub_spares_assignments as $new_sub_assignment)

                  @if($thead_spares)
                  <?php $thead_spares = false; ?>

      <h3>Запчасти / Материалы ({{ $new_sub_assignment->d_table_work_direction }})</h3>

      <div class="card card-p">
          <form method="post" action="">
              <table class="table">
                  <thead>
                  <tr>
                      <th scope="col">Деталь</th>
                      <th scope="col">Артикул</th>
                      <th scope="col">Ед. измерения</th>
                      <th scope="col">Кол-во</th>
                      <th scope="col">Цена</th>
                      <th scope="col">Валюта</th>
                      <th scope="col">Сумма</th>
                      <th scope="col">Действие</th>
                  </tr>
                  </thead>
                  <tbody id="dynamic-{{ substr($new_sub_assignment->spares_row_index, 0, 1) }}-2">

                  @endif

                  <tr style="background-color: {{ ($new_sub_assignment->spares_is_locked == 'active') ? '#f0f0f0' : 'transparent' }} ;" data-tr-row="{{ $new_sub_assignment->spares_row_index }}">
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <input disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="{{ $new_sub_assignment->d_table_spares_detail }}">
                              @else
                                  <input data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="{{ $new_sub_assignment->d_table_spares_detail }}">
                              @endif
                          </label>
                      </td>
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <input disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="{{ $new_sub_assignment->d_table_spares_vendor_code }}">
                              @else
                                  <input data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="{{ $new_sub_assignment->d_table_spares_vendor_code }}">
                              @endif
                          </label>
                      </td>
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <input disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="{{ $new_sub_assignment->d_table_spares_unit_measurements }}">
                              @else
                                  <input data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="{{ $new_sub_assignment->d_table_spares_unit_measurements }}">
                              @endif
                          </label>
                      </td>
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <input disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="{{ $new_sub_assignment->d_table_spares_quantity }}">
                              @else
                                  <input data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="{{ $new_sub_assignment->d_table_spares_quantity }}">
                              @endif
                          </label>
                      </td>
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <input disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="{{ $new_sub_assignment->d_table_spares_price }}">
                              @else
                                  <input data-row="{{ $new_sub_assignment->spares_row_index }}" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="{{ $new_sub_assignment->d_table_spares_price }}">
                              @endif
                          </label>
                      </td>
                      <td>
                          <label>
                              @if($new_sub_assignment->spares_is_locked == 'active')
                                  <select disabled data-row="{{ $new_sub_assignment->spares_row_index }}" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                      @else
                                          <select data-row="{{ $new_sub_assignment->spares_row_index }}" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                              @endif
                                              @for($i=0; $i < count($currency_arr); $i++)
                                                  @if($currency_arr[$i] == $new_sub_assignment->d_table_spares_currency)
                                                      <option selected value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                                  @else
                                                      <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                                  @endif
                                              @endfor
                                          </select>
                          </label>
                      </td>
                      <td class="sum-row">
                          <strong data-row="{{ $new_sub_assignment->spares_row_index }}">{{ $new_sub_assignment->spares_sum_row }}</strong>
                      </td>
                      <td>
                          <button data-row="{{ $new_sub_assignment->spares_row_index }}" type="button" class="add">+</button>
                          <button data-row="{{ $new_sub_assignment->spares_row_index }}" type="button" class="del">-</button>
                          <button data-row="{{ $new_sub_assignment->spares_row_index }}" type="button" onclick="buttonUnlocked(this)" style="display: {{ ($new_sub_assignment->spares_is_locked == 'active') ? 'none' : 'block' }} ;"><i class="icon-unlocked2"></i></button>
                          <button data-row="{{ $new_sub_assignment->spares_row_index }}" type="button" onclick="buttonLock(this)" style="display: {{ ($new_sub_assignment->spares_is_locked == 'active') ? 'block' : 'none' }} ;"><i class="icon-lock2"></i></button>
                      </td>
                  </tr>
                  <?php $j++; ?>
                  <?php

                  if ($new_sub_assignment->d_table_spares_currency === 'USD') {
                      $total_spares_sum += round(($new_sub_assignment->spares_sum_row)/$usd);
                  }
                  else if ($new_sub_assignment->d_table_spares_currency === 'EUR') {
                      $total_spares_sum += round(($new_sub_assignment->spares_sum_row)/$eur);
                  }
                  else {
                      $total_spares_sum += $new_sub_assignment->spares_sum_row;
                  }

                  ?>
                  @endforeach

                  </tbody>
              </table>
          </form>
          <hr/>
          <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
          <h4>Итого: <?=$total_spares_sum?> лей</h4>
      </div>


      <hr/>
    @else

      <h3>Запчасти / Материалы ({{ $work_direction_name }})</h3>
<div class="card card-p">
    <form method="post" action="">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Деталь</th>
                <th scope="col">Артикул</th>
                <th scope="col">Ед. измерения</th>
                <th scope="col">Кол-во</th>
                <th scope="col">Цена</th>
                <th scope="col">Валюта</th>
                <th scope="col">Сумма</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>

            <tbody id="dynamic-{{ $work_direction_id }}-2">

            <tr data-tr-row="{{ $work_direction_id }}2000">
                <td>
                    <label>
                        <input data-row="{{ $work_direction_id }}2000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="{{ $work_direction_id }}2000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="{{ $work_direction_id }}2000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="{{ $work_direction_id }}2000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="{{ $work_direction_id }}2000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <select data-row="{{ $work_direction_id }}2000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                            @for($i=0; $i < count($currency_arr); $i++)
                                <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                            @endfor
                        </select>
                    </label>
                </td>
                <td class="sum-row">
                    <strong data-row="{{ $work_direction_id }}2000">0</strong>
                </td>
                <td>
                    <button data-row="{{ $work_direction_id }}2000" type="button" class="add">+</button>
                    <button data-row="{{ $work_direction_id }}2000" type="button" class="del">-</button>
                    <button data-row="{{ $work_direction_id }}2000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                    <button data-row="{{ $work_direction_id }}2000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <hr/>
    <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
    <h4>Итого: 0 лей</h4>
</div>

      <hr/>
    @endif

    </div><!--Конец  Dynamic-With-Data//-->
    @endif
    @endforeach


    <!--  dynamic-1  //-->

    <div class="dyn-tab dynamic-1" style="display: none;">

      <h3>Выполненная работа (Разборка-сборка)</h3>
        <div class="card card-p">
            <form method="post" action="">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Рабочая зона</th>
                    <th scope="col">Время начала работ</th>
                    <th scope="col">Время окончания работ</th>
                    <th scope="col">Ответственный сотрудник</th>
                    <th scope="col">Список выполненных работ</th>
                    <th scope="col">Кол-во</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Валюта</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody id="dynamic-1-1">

                <?php //echo '<pre>'.print_r($new_sub_work_assignments->count(),true).'</pre>'; ?>

                <tr data-tr-row="11000">
                    <td class="number_sub_assignment">
                        <strong></strong>
                    </td>
                    <td>
                        <label>
                            <select onchange="workzoneSelect(this)" data-row="11000" name="d_table_workzone" style="max-width: 100px;">
                                <option value="">Выбрать</option>
                                @foreach($workzone_data as $work_data)
                                    <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class="input-data-startwork" data-row="11000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input data-row="11000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                        </label>
                    </td>
                    <td>
                        <label>
                            <select onchange="employeeSelect(this)" data-row="11000" name="d_table_responsible_officer" style="max-width: 100px;">
                                <option value="">Выбрать</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input data-row="11000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input data-row="11000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input data-row="11000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                        </label>
                    </td>
                    <td>
                        <label>
                            <select data-row="11000" onchange="dataTransmission(this)" name="d_table_currency">
                                @for($i=0; $i < count($currency_arr); $i++)
                                    <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                @endfor
                            </select>
                        </label>
                    </td>
                    <td class="sum-row">
                        <strong data-row="11000">0</strong>
                    </td>
                    <td>
                        <button data-row="11000" type="button" class="add">+</button>
                        <button data-row="11000" type="button" class="del">-</button>
                        <button data-row="11000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                        <button data-row="11000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Разборка-сборка)</h3>
<div class="card card-p">
    <form method="post" action="">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Деталь</th>
                <th scope="col">Артикул</th>
                <th scope="col">Ед. измерения</th>
                <th scope="col">Кол-во</th>
                <th scope="col">Цена</th>
                <th scope="col">Валюта</th>
                <th scope="col">Сумма</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>

            <tbody id="dynamic-1-2">

            <tr data-tr-row="12000">
                <td>
                    <label>
                        <input data-row="12000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="12000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="12000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="12000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                    </label>
                </td>
                <td>
                    <label>
                        <input data-row="12000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                    </label>
                </td>
                <td>
                    <label>
                        <select data-row="12000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                            <option value="MDL">MDL</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </label>
                </td>
                <td class="sum-row">
                    <strong data-row="12000">0</strong>
                </td>
                <td>
                    <button data-row="12000" type="button" class="add">+</button>
                    <button data-row="12000" type="button" class="del">-</button>
                    <button data-row="12000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                    <button data-row="12000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <hr/>
    <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
    <h4>Итого: 0 лей</h4>
</div>


      <hr/>

    </div><!--Конец  dynamic-1//-->


    <!--  dynamic-2  //-->

    <div class=" dyn-tab dynamic-2" style="display: none;">

      <h3>Выполненная работа (Электрика)</h3>
        <div class="card card-p">
            <form method="post"  action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-2-1">

                    <?php //echo '<pre>'.print_r($new_sub_work_assignments->count(),true).'</pre>'; ?>

                    <tr data-tr-row="21000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="21000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="21000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="21000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="21000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="21000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="21000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="21000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="21000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="21000">0</strong>
                        </td>
                        <td>
                            <button data-row="21000" type="button" class="add">+</button>
                            <button data-row="21000" type="button" class="del">-</button>
                            <button data-row="21000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="21000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Электрика)</h3>
        <div class="card card-p">
            <form method="post"  action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-2-2">

                    <tr data-tr-row="22000">
                        <td>
                            <label>
                                <input data-row="22000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="22000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="22000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="22000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="22000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="22000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="22000">0</strong>
                        </td>
                        <td>
                            <button data-row="22000" type="button" class="add">+</button>
                            <button data-row="22000" type="button" class="del">-</button>
                            <button data-row="22000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="22000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

    </div><!--Конец  dynamic-2//-->


    <!--  dynamic-3  //-->

    <div class="dyn-tab dynamic-3 " style="display: none;">

      <h3>Выполненная работа (Слесарка)</h3>
        <div class="card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-3-1">

                    <tr data-tr-row="31000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="31000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="31000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="31000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="31000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="31000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="31000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="31000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="31000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="31000">0</strong>
                        </td>
                        <td>
                            <button data-row="31000" type="button" class="add">+</button>
                            <button data-row="31000" type="button" class="del">-</button>
                            <button data-row="31000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="31000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>

            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Слесарка)</h3>
        <div class="card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-3-2">

                    <tr data-tr-row="32000">
                        <td>
                            <label>
                                <input data-row="32000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="32000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="32000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="32000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="32000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="32000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="32000">0</strong>
                        </td>
                        <td>
                            <button data-row="32000" type="button" class="add">+</button>
                            <button data-row="32000" type="button" class="del">-</button>
                            <button data-row="32000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="32000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

    </div><!--Конец  dynamic-3//-->


    <!--  dynamic-4  //-->

    <div class="dyn-tab dynamic-4" style="display: none;">

      <h3>Выполненная работа (Рихтовка)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-4-1">

                    <tr data-tr-row="41000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="41000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="41000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="41000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="41000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="41000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="41000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="41000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="41000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="41000">0</strong>
                        </td>
                        <td>
                            <button data-row="41000" type="button" class="add">+</button>
                            <button data-row="41000" type="button" class="del">-</button>
                            <button data-row="41000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="41000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>
      <hr/>

      <h3>Запчасти / Материалы (Рихтовка)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-4-2">

                    <tr data-tr-row="42000">
                        <td>
                            <label>
                                <input data-row="42000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="42000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="42000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="42000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="42000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="42000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="42000">0</strong>
                        </td>
                        <td>
                            <button data-row="42000" type="button" class="add">+</button>
                            <button data-row="42000" type="button" class="del">-</button>
                            <button data-row="42000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="42000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>

        </div>

      <hr/>

    </div><!--Конец  dynamic-4//-->


    <!--  dynamic-5  //-->

    <div class="dyn-tab dynamic-5 " style="display: none;">

      <h3>Выполненная работа (Покраска)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-5-1">

                    <tr data-tr-row="51000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="51000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="51000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="51000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="51000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="51000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="51000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="51000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="51000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="51000">0</strong>
                        </td>
                        <td>
                            <button data-row="51000" type="button" class="add">+</button>
                            <button data-row="51000" type="button" class="del">-</button>
                            <button data-row="51000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="51000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Покраска)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-5-2">

                    <tr data-tr-row="52000">
                        <td>
                            <label>
                                <input data-row="52000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="52000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="52000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="52000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="52000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="52000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="52000">0</strong>
                        </td>
                        <td>
                            <button data-row="52000" type="button" class="add">+</button>
                            <button data-row="52000" type="button" class="del">-</button>
                            <button data-row="52000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="52000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>
        </div>
      <hr/>

    </div><!--Конец  dynamic-5//-->


    <!--  dynamic-6  //-->

    <div class="dyn-tab dynamic-6" style="display: none;">

      <h3>Выполненная работа (Детэйлинг)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-6-1">

                    <tr data-tr-row="61000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="61000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="61000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="61000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="61000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="61000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="61000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="61000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="61000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="61000">0</strong>
                        </td>
                        <td>
                            <button data-row="61000" type="button" class="add">+</button>
                            <button data-row="61000" type="button" class="del">-</button>
                            <button data-row="61000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="61000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Детэйлинг)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-6-2">

                    <tr data-tr-row="62000">
                        <td>
                            <label>
                                <input data-row="62000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="62000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="62000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="62000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="62000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="62000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="62000">0</strong>
                        </td>
                        <td>
                            <button data-row="62000" type="button" class="add">+</button>
                            <button data-row="62000" type="button" class="del">-</button>
                            <button data-row="62000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="62000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

    </div><!--Конец  dynamic-6//-->


    <!--  dynamic-7  //-->

    <div class="dyn-tab dynamic-7" style="display: none;">

      <h3>Выполненная работа (Малярка)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Рабочая зона</th>
                        <th scope="col">Время начала работ</th>
                        <th scope="col">Время окончания работ</th>
                        <th scope="col">Ответственный сотрудник</th>
                        <th scope="col">Список выполненных работ</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody id="dynamic-7-1">

                    <tr data-tr-row="71000">
                        <td class="number_sub_assignment">
                            <strong></strong>
                        </td>
                        <td>
                            <label>
                                <select onchange="workzoneSelect(this)" data-row="71000" name="d_table_workzone" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($workzone_data as $work_data)
                                        <option value="{{ $work_data->id }}">{{ $work_data->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="71000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_start" value="{{ $assignment->date_of_creation }}">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="71000" onclick="dataTransmission(this)" style="width: 72px;" type="date" name="d_table_time_finish" value="0000-00-00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select onchange="employeeSelect(this)" data-row="71000" name="d_table_responsible_officer" style="max-width: 100px;">
                                    <option value="">Выбрать</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="71000" onblur="dataTransmission(this)" rows="1" cols="15" name="d_table_list_completed_works" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="71000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="71000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="71000" onchange="dataTransmission(this)" name="d_table_currency">
                                    @for($i=0; $i < count($currency_arr); $i++)
                                        <option value="{{ $currency_arr[$i] }}">{{ $currency_arr[$i] }}</option>
                                    @endfor
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="71000">0</strong>
                        </td>
                        <td>
                            <button data-row="71000" type="button" class="add">+</button>
                            <button data-row="71000" type="button" class="del">-</button>
                            <button data-row="71000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="71000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <h4>Итого: 0 лей</h4>
        </div>


      <hr/>

      <h3>Запчасти / Материалы (Малярка)</h3>
        <div class=" card card-p">
            <form method="post" action="">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Деталь</th>
                        <th scope="col">Артикул</th>
                        <th scope="col">Ед. измерения</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Валюта</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>

                    <tbody id="dynamic-7-2">

                    <tr data-tr-row="72000">
                        <td>
                            <label>
                                <input data-row="72000" onblur="dataTransmission(this)" style="width: 200px;" type="text" name="d_table_spares_detail" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="72000" onblur="dataTransmission(this)" type="text" name="d_table_spares_vendor_code" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="72000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_unit_measurements" value="">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="72000" onblur="dataTransmission(this)" style="width: 20px;" type="text" name="d_table_spares_quantity" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input data-row="72000" onblur="dataTransmission(this)" style="width: 60px;" type="text" name="d_table_spares_price" value="00.00">
                            </label>
                        </td>
                        <td>
                            <label>
                                <select data-row="72000" onchange="dataTransmission(this)" name="d_table_spares_currency">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </label>
                        </td>
                        <td class="sum-row">
                            <strong data-row="72000">0</strong>
                        </td>
                        <td>
                            <button data-row="72000" type="button" class="add">+</button>
                            <button data-row="72000" type="button" class="del">-</button>
                            <button data-row="72000" type="button" onclick="buttonUnlocked(this)"><i class="icon-unlocked2"></i></button>
                            <button data-row="72000" type="button" onclick="buttonLock(this)" style="display: none;"><i class="icon-lock2"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <hr/>
            <i style="color: green; cursor: pointer;" class="icon-wallet"> Склад</i>
            <h4>Итого: 0 лей</h4>

        </div>

      <hr/>

    </div><!--Конец  dynamic-7//-->

  </div><!-- txt_1 Наряд-->


  <div id="txt_2">

    <div id="appVue">

    <!-- Модальное окно поиска наряда -->
    <div class="modal fade" id="searchModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="card card-p">
            <form action="{{ url('/admin/assignments/search_assignment') }}" method="POST">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="searchModalLabel">Форма поиска</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-row">

                                {{-- Модель и марка --}}
                                {{-- Марка : от неё будут подтягивать подсказки . Используется Typeahead --}}
                                <div class="form-group">
                                    <label>Марка машины</label>
                                    <input v-model.lazy="mark2" type="text" name="car_brand" id="carBrand" class="form-control typeahead">
                                </div>

                                {{-- Модель : подтягивается с базы --}}
                                {{-- ... --}}
                                <div class="form-group">
                                    <label>Модель машины</label>
                                    <select v-model.lazy="model2" id="carModel" name="car_model" class="form-control">
                                    </select>
                                </div>

                                {{-- Год выпуска --}}
                                <div class="form-group">
                                    <label>Год выпуска</label>
                                    <input type="number" min="1900" max="2099" step="1" value="" name="release_year" id="releaseyear" class="form-control typeahead">
                                </div>

                                {{-- Регистрационный номер --}}
                                <div class="form-group">
                                    <label>Регистрационный номер</label>
                                    <input type="text" name="reg_number" id="regnumber" class="form-control">
                                </div>

                                {{-- VIN --}}
                                <div class="form-group">
                                    <label>VIN</label>
                                    <input type="text" name="vin_number" id="vinnumber" class="form-control">
                                </div>

                                {{-- Обьем мотора --}}
                                <div class="form-group">
                                    <label>Объем двигателя</label>
                                    <input type="number" min="0" max="20000" step="1" name="engine_capacity" id="enginecapacity" class="form-control">
                                </div>

                                {{-- Тип топлива --}}
                                <div class="form-group">
                                    <label>Тип топлива</label>
                                    <select name="fuel_type" id="fueltype" class="form-control">
                                        <option value="Бензин">Бензин</option>
                                        <option value="Дизель">Дизель</option>
                                        <option value="Hybrid (Бензин)">Hybrid (Бензин)</option>
                                        <option value="Hybrid (Дизель)">Hybrid (Дизель)</option>
                                        <option value="Plug-in Hybrid (Бензин)">Plug-in Hybrid (Бензин)</option>
                                        <option value="Plug-in Hybrid (Дизель)">Plug-in Hybrid (Дизель)</option>
                                        <option value="Газ (Пропан) / Бензин">Газ (Пропан) / Бензин</option>
                                        <option value="Газ (Метан) / Бензин">Газ (Метан) / Бензин</option>
                                        <option value="Газ (Пропан)">Газ (Пропан)</option>
                                        <option value="Газ (Метан)">Газ (Метан)</option>
                                        <option value="Электро">Электро</option>
                                    </select>
                                </div>

                                {{-- Номер наряда --}}
                                <div class="form-group">
                                    <label>Номер наряда</label>
                                    <input type="text" name="num_assignment" class="form-control">
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>
    <!-- Конец Модальное окно поиска наряда -->
      
      <h3>Информация автомобиля: {{ $assignment->car_name }}</h3>
<div class="card card-p">
    {{-- Форма изменения машины для клиента --}}
    <form action="{{ url('admin/cars_in_service/update/'.$assignment->car_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- ID клиента --}}
        <input type="hidden" name="client_id" value="{{ $assignment->client_id }}">
        {{-- Название машины --}}
        <input type="hidden" name="car_general_name" :value="mark+' '+model">

        <div class="form-row">

            {{-- Модель и марка --}}
            {{-- Марка : от неё будут подтягивать подсказки . Используется Typeahead --}}
            <div class="form-group col-md-6">
                <label>Марка машины</label>
                <p class="btn btn-light form-control" id="pCarBrandInf" onclick="brandInfo()" style="text-align: left;">{{ $assignment->car_brand }}</p>
                <input v-model.lazy="mark" name="car_brand" id="carBrandInf" class="form-control typeahead" required style="display: none;">
            </div>

            {{-- Модель : подтягивается с базы --}}
            {{-- ... --}}
            <div class="form-group col-md-6">
                <label>Модель машины</label>
                <p class="btn btn-light form-control" id="pCarModelInf" onclick="modelInfo()" style="text-align: left;">{{ $assignment->car_model }}</p>
                <select v-model.lazy="model" id="carModelInf" name="car_model" class="form-control" required style="display: none;">
                </select>
            </div>

        </div>

        <div class="form-row">

            {{-- Скрипт на автоматический пересчёт - внизу, в секции custom_scripts --}}

            {{-- Новые поля --}}
            {{-- Год выпуска --}}
            <div class="form-group col-md-4">
                <label>Год выпуска</label>
                <input type="number" min="1900" max="2099" step="1" value="2019" name="release_year" id="releaseyear" class="form-control typeahead" value="{{ $assignment->car_year }}" required>
            </div>

            {{-- Тип топлива --}}
            <div class="form-group col-md-4">
                <label>Тип топлива</label>
                <select name="fuel_type" id="fueltype" class="form-control" required>
                    <option <?=($assignment->car_fuel_type === 'Бензин')?'selected':''?> value="Бензин">Бензин</option>
                    <option <?=($assignment->car_fuel_type === 'Дизель')?'selected':''?> value="Дизель">Дизель</option>
                    <option <?=($assignment->car_fuel_type === 'Hybrid (Бензин)')?'selected':''?> value="Hybrid (Бензин)">Hybrid (Бензин)</option>
                    <option <?=($assignment->car_fuel_type === 'Hybrid (Дизель)')?'selected':''?> value="Hybrid (Дизель)">Hybrid (Дизель)</option>
                    <option <?=($assignment->car_fuel_type === 'Plug-in Hybrid (Бензин)')?'selected':''?> value="Plug-in Hybrid (Бензин)">Plug-in Hybrid (Бензин)</option>
                    <option <?=($assignment->car_fuel_type === 'Plug-in Hybrid (Дизель)')?'selected':''?> value="Plug-in Hybrid (Дизель)">Plug-in Hybrid (Дизель)</option>
                    <option <?=($assignment->car_fuel_type === 'Газ (Пропан) / Бензин')?'selected':''?> value="Газ (Пропан) / Бензин">Газ (Пропан) / Бензин</option>
                    <option <?=($assignment->car_fuel_type === 'Газ (Метан) / Бензин')?'selected':''?> value="Газ (Метан) / Бензин">Газ (Метан) / Бензин</option>
                    <option <?=($assignment->car_fuel_type === 'Газ (Пропан)')?'selected':''?> value="Газ (Пропан)">Газ (Пропан)</option>
                    <option <?=($assignment->car_fuel_type === 'Газ (Метан)')?'selected':''?> value="Газ (Метан)">Газ (Метан)</option>
                    <option <?=($assignment->car_fuel_type === 'Электро')?'selected':''?> value="Электро">Электро</option>
                </select>
            </div>

            {{-- Обьем мотора --}}
            <div class="form-group col-md-4">
                <label>Объем двигателя</label>
                <input type="number" min="0" max="20000" step="1" name="engine_capacity" id="enginecapacity" class="form-control" value="{{ $assignment->car_engine_capacity }}" required>
            </div>

        </div>

        <div class="form-row">

            {{-- Цвет --}}
            <div class="form-group col-md-4">
                <label>Цвет</label>
                <div id="cp2" class="input-group colorpicker-component">
                    <span class="input-group-addon"><i style="width:35px; height:35px; display:flex; border: 2px solid rgb(97, 97, 97);"></i></span>
                    <input type="text" name="car_color" value="{{ $assignment->car_color }}" class="form-control"/>
                </div>
            </div>

            {{-- Пробег --}}
            {{-- Километры --}}
            <div class="form-group col-md-4">
                {{-- ... --}}
                <label>Пробег в километрах</label>
                <input type="number" name="mileage_km" class="form-control" min="0" id="mileageKM" step="any" value="{{ $assignment->car_mileage_km }}" required>
            </div>

            {{-- Мили --}}
            <div class="form-group col-md-4">
                <label>Пробег в милях</label>
                <input type="number" name="mileage_miles" class="form-control" min="0" id="mileageMiles" step="any" required>
            </div>

        </div>

        <div class="form-row">

            {{-- Регистрационный номер --}}
            <div class="form-group col-md-6">
                <label>Регистрационный номер</label>
                <input type="text" name="reg_number" id="regnumber" class="form-control" value="{{ $assignment->car_reg_number }}" required>
            </div>

            {{-- VIN --}}
            <div class="form-group col-md-6">
                <label>VIN</label>
                <input type="text" name="vin_number" id="vinnumber" class="form-control" value="{{ $assignment->car_vin_number }}" required>
            </div>

        </div>

        <button id="new-car" type="submit" class="btn btn-primary">
            Сохранить
        </button>
    </form>
</div>



        <hr>
        {{-- Фотографии : вывод --}}
        <h3>Фотографии:</h3>
        <div class="card card-p">
            <div class="row">

                <div class="col-sm-4">

                    {{-- Цикл вывода фотографий --}}
                    <p>Принятая машина:</p>
                    @foreach($accepted_image_urls as $image_url)
                        <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                               data-image="{{ Storage::url($image_url) }}"
                               data-target="#image-gallery">
                                <img class="img-thumbnail"
                                     src="{{ Storage::url($image_url) }}"
                                     alt="Another alt text">
                            </a>
                        </div>
                    @endforeach

                </div>{{-- /row --}}

                <div class="col-sm-4">

                    {{-- Цикл вывода фотографий --}}
                    <p>Процесс ремонта:</p>
                    @foreach($repair_image_urls as $image_url)
                        <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                               data-image="{{ Storage::url($image_url) }}"
                               data-target="#image-gallery">
                                <img class="img-thumbnail"
                                     src="{{ Storage::url($image_url) }}"
                                     alt="Another alt text">
                            </a>
                        </div>
                    @endforeach

                </div>{{-- /row --}}

                <div class="col-sm-4">

                    {{-- Цикл вывода фотографий --}}
                    <p>Выдача готовой:</p>
                    @foreach($finished_image_urls as $image_url)
                        <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                            <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                               data-image="{{ Storage::url($image_url) }}"
                               data-target="#image-gallery">
                                <img class="img-thumbnail"
                                     src="{{ Storage::url($image_url) }}"
                                     alt="Another alt text">
                            </a>
                        </div>
                    @endforeach

                </div>{{-- /row --}}

                {{-- Модальное окно для вывода лайтбокса --}}
                <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="image-gallery-title"></h4>
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <center>
                                    <img id="image-gallery-image" class="img-responsive" src="">
                                </center>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light float-left" id="show-previous-image"><=
                                </button>

                                <button type="button" id="show-next-image" class="btn btn-light float-right">=>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Конец модального окна для вывода лайтбокса --}}
            </div>
        </div>

        {{-- Конец вывод фотографий --}}

        {{-- Фотографии : переход на страницу загрузки --}}
        <a href="{{ url('/admin/assignments/'.$assignment->id.'/add_photo_page') }}">
          <div class="btn btn-light">
            К загрузке фотографий
          </div>
        </a>

        {{-- Удаление фотографий : переход на страницу --}}
        <a href="{{ url('/admin/assignments/'.$assignment->id.'/delete_photos_page') }}">
          <div class="btn btn-dark">
            К удалению фотографий
          </div>
        </a><br>
        <br>

      </div><!-- appVue -->

  </div><!-- txt_2 Информация автомобиля-->


  <div id="txt_3">

      <h3>Информация клиента: {{ $assignment->client_fio }}</h3>
<div class="card card-p">
    <form action="{{ url('admin/clients/update_client/'.$assignment->client_id) }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Имя</label>
                <input class="form-control" type="name" name="name" value="{{ $assignment->client_name }}" required>
            </div>
            <div class="form-group col-md-6">
                <label>Фамилия</label>
                <input class="form-control" type="text" name="surname" value="{{ $assignment->client_surname }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Организация</label>
                <input class="form-control" type="text" name="organization" value="{{ $assignment->client_organization }}">
            </div>
            <div class="form-group col-md-6">
                <label>Телефон</label>
                <input class="form-control" type="number" name="phone" value="{{ $assignment->client_phone }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Баланс</label>
                <input class="form-control" type="number" name="balance" value="{{ $assignment->client_balance }}">
            </div>
            <div class="form-group col-md-6">
                <label>Скидка</label>
                <input class="form-control" type="number" name="discount" value="{{ $assignment->client_discount }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Сохранить
        </button>

    </form>
</div>


  </div><!-- txt_3 Информация клиента-->
  
  
  <div id="txt_4">
      
      <h3>Доходная часть</h3>
      

<div class="card card-p">
    <form class="text-center" method="POST" action="{{ url('/admin/profitability/profitability_index') }}">
        @csrf
        <div class="form-row mb-3">
            <div class="form-group  text-center col-md-6" style="margin: 0 auto;">
                <input class="py-1" type="number" name="usd_currency" value="{{ $usd }}" step="0.00001">
                <input class="py-1" type="number" name="eur_currency" value="{{ $eur }}" step="0.00001">
                <input class="py-1" type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </div>


    </form>

    <br/>

    {{-- Тип операции --}}
    <input type="hidden" name="type_operation" value="Доход">
    {{-- Категория --}}
    <input type="hidden" name="category" value="">
    {{-- Тег --}}
    <input type="hidden" name="tag" value="">
    {{-- Описание --}}
    <input type="hidden" name="comment" value="">
    <table class="table table-deb table-income">
        <thead>
        <tr>
            <th scope="col">Счет</th>
            <th scope="col">Сумма(MDL)</th>
            <th scope="col">Дата</th>
            <th scope="col">Действие</th>
        </tr>
        </thead>
        <tbody>
        <tr>

            <form action="{{ url('/admin/manage_assignment/add_income_entry') }}" method="POST">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                <input type="hidden" name="currency" value="MDL">
                <input type="hidden" name="basis" value="Закрытие наряда">
                <input type="hidden" name="description" value="Закрытие наряда">
                <td>
                    <select name="account_id" class="form-control">
                        @foreach($accounts as $account)
                            @if(isset($_GET['account_id']) && $_GET['account_id'] == $account->id)
                                <option value="{{ $account->id }}" selected>{{ $account->name }}</option>
                            @else
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="amount" class="form-control" value="" required>
                </td>
                <td>
                    <input type="text" name="disabled-date" class="form-control" disabled value="{{ date('Y-m-d H:i:s') }}">
                    <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}">
                </td>
                <td>
                    <button class="icon-plus-custom" type="submit">+</button>
                </td>
            </form>

        </tr>
        </tbody>

    </table>

    <p class="text-left mt-3">*Для закрытия наряда внесите полную сумму и загрузите акты принятия и выдачи машины клиенту</p>
    <div class="form-row">
        <div class="ml-auto form-group col-md-3" style="">
            <h4>Всего: {{ $sum_income }} лей</h4>
            <h4>Остаток: {{ $sum_expense - $sum_income }} лей</h4>
        </div>
    </div>


</div>

      
      @if($sum_income == $sum_expense)
      <form action="{{ url('/admin/assignments/closed_assignment') }}" method="POST">
      @csrf
        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
        <input type="hidden" name="income" value="{{ $sum_income }}">
        <input type="hidden" name="account_id" value="<?=(isset($_GET['account_id']))?$_GET['account_id']:''?>">
        <input type="hidden" name="closed_assignment" value="closed">
        <button type="submit" class="btn btn-primary">Закрыть наряд</button>
      </form>
      @endif
  
  </div><!-- txt_4 Доходная часть-->
  
  
  <div id="txt_5">
      <p>Настройки печати</p>
  </div>


</div><!-- tabs-menu -->


@endsection

@section('custom_scripts')

<script>
          var appVue = new Vue({
              el: '#appVue',
              data: {
                  mark2: '',
                  model2: '',
                  mark: '',
                  model: ''
              }

          });
</script>

<script type="text/javascript">

var assignmentId = '{{ $assignment->id }}';

/**
 * Динамическая таблица
 */
var DynamicTable = (function (GLOB) {
    var RID = 0;
    return function (tBody) {
        /* Если ф-цию вызвали не как конструктор фиксим этот момент: */
        if (!(this instanceof arguments.callee)) {
            return new arguments.callee.apply(arguments);
        }
        //Делегируем прослушку событий элементу tbody
        tBody.onclick = function(e) {
            var evt = e || GLOB.event,
                trg = evt.target || evt.srcElement;
            if (trg.className && trg.className.indexOf("add") !== -1) {
                _addRow(trg.parentNode.parentNode, tBody);
            } else if (trg.className && trg.className.indexOf("del") !== -1) {
                tBody.rows.length > 1 && _delRow(trg.parentNode.parentNode, tBody);
            }
        };
        var _rowTpl = tBody.rows[0].cloneNode(true);
        // Корректируем имена элементов формы
        var _correctNames = function (row) {
            var elements = row.getElementsByTagName("*");
            for (var i = 0; i < elements.length; i += 1) {
                if (elements.item(i).name) {
                    if (elements.item(i).type &&
                        elements.item(i).type === "radio" &&
                        elements.item(i).className &&
                        elements.item(i).className.indexOf("glob") !== -1)
                    {
                        elements.item(i).value = RID;
                    } else {
                        elements.item(i).name = RID + "["+ elements.item(i).name +"]";
                    }
                }
            }
            RID++;
            return row;
        };
        var _addRow = function (before, tBody) {
            var newNode = _correctNames(_rowTpl.cloneNode(true));
            tBody.insertBefore(newNode, before.nextSibling);

            //Изменяем индексы в строках таблицы
            var attrValStr = $(before).attr('data-tr-row').slice(0, -3);
            var attrValColl = '-'+attrValStr[0]+'-'+attrValStr[1];
            attrValStr = attrValStr + '000';
            var attrVal = parseInt(attrValStr);

            //console.log(attrValStr);

            $('#dynamic'+attrValColl+'>[data-tr-row]').each(function(k,elem) {
                if (attrVal == $(elem).attr('data-tr-row') && k != 0) {

                  var maximum = null;
                  $('#dynamic'+attrValColl+'>[data-tr-row]').each(function(i,el){
                    var value = parseInt($(el).attr('data-tr-row'));
                    maximum = (value > maximum) ? value : maximum;
                    k = maximum - attrVal + 1;
                  });

                  $(elem).attr('data-tr-row', (attrVal + k));
                  $(elem).find('[data-row]').attr('data-row', (attrVal + k));
                }
            });
        };
        var _delRow = function (row, tBody) {
            if (confirm("Вы уверены что хотите удалить ?")) {
              const valueRow = $(row).attr('data-tr-row');
              $.ajax({
                url: "{{ route('add_new_sub_assignment') }}",
                type: "POST",
                data: {"_token": "{{ csrf_token() }}", valueRow:valueRow, assignmentId:assignmentId},
                headers: {
                  'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                  //console.log(data);
                  tBody.removeChild(row);
                },
                error: function (msg) {
                  alert('Строку нельзя удалить !');
                }
              });
            }
        };
        _correctNames(tBody.rows[0]);
    };
})(this);
</script>

<script>
    if (document.getElementById("dynamic-1-1")) new DynamicTable( document.getElementById("dynamic-1-1") );
    if (document.getElementById("dynamic-1-1")) new DynamicTable( document.getElementById("dynamic-1-2") );
    if (document.getElementById("dynamic-2-1")) new DynamicTable( document.getElementById("dynamic-2-1") );
    if (document.getElementById("dynamic-2-1")) new DynamicTable( document.getElementById("dynamic-2-2") );
    if (document.getElementById("dynamic-3-1")) new DynamicTable( document.getElementById("dynamic-3-1") );
    if (document.getElementById("dynamic-3-1")) new DynamicTable( document.getElementById("dynamic-3-2") );
    if (document.getElementById("dynamic-4-1")) new DynamicTable( document.getElementById("dynamic-4-1") );
    if (document.getElementById("dynamic-4-1")) new DynamicTable( document.getElementById("dynamic-4-2") );
    if (document.getElementById("dynamic-5-1")) new DynamicTable( document.getElementById("dynamic-5-1") );
    if (document.getElementById("dynamic-5-1")) new DynamicTable( document.getElementById("dynamic-5-2") );
    if (document.getElementById("dynamic-6-1")) new DynamicTable( document.getElementById("dynamic-6-1") );
    if (document.getElementById("dynamic-6-1")) new DynamicTable( document.getElementById("dynamic-6-2") );
    if (document.getElementById("dynamic-7-1")) new DynamicTable( document.getElementById("dynamic-7-1") );
    if (document.getElementById("dynamic-7-1")) new DynamicTable( document.getElementById("dynamic-7-2") );
</script>


<style>
.btn:focus, .btn:active, button:focus, button:active {
  outline: none !important;
  box-shadow: none !important;
}

#image-gallery .modal-footer{
  display: block;
}

.thumb{
  margin-top: 15px;
  margin-bottom: 15px;
}
</style>

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
       url: "{{ url('/admin/assignments/view/'.$assignment->id) }}",
       data: {
         order:order,
         _token: '{{csrf_token()}}'
       },
       success: function(response) {
           if (response.status == "success") {
             //console.log(response);
           } else {
             //console.log(response);
           }
       }
     });

   }
 });

</script>

<script>

let modalId = $('#image-gallery');

$(document)
  .ready(function () {

    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#show-previous-image, #show-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#show-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#show-previous-image')
          .hide();
      }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#show-next-image, #show-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
          .text($sel.data('title'));
        $('#image-gallery-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }
  });

// build key actions
$(document)
  .keydown(function (e) {
    switch (e.which) {
      case 37: // left
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
          $('#show-previous-image')
            .click();
        }
        break;

      case 39: // right
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
          $('#show-next-image')
            .click();
        }
        break;

      default:
        return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
  });
</script>

<!------------------- Скрипты для динамических таблиц ---------------------->
<script type="text/javascript">
  var workzoneData = '<?=json_encode($workzone_data,JSON_UNESCAPED_UNICODE) ?>';
  workzoneData = JSON.parse(workzoneData);
  var employeeData = '<?=json_encode($employees,JSON_UNESCAPED_UNICODE) ?>';
  employeeData = JSON.parse(employeeData);
  var workDirections = '<?=json_encode($work_directions,JSON_UNESCAPED_UNICODE) ?>';
  workDirections = JSON.parse(workDirections);

  //Кнопка Подробнее
  function readMore() {
    $('#read-more').toggle();
  }

  //Кнопка Таблица расчета рентабельности
  function calculationProfitability() {
    $('#table-calculation-profitability').toggle();
    if ($('#table-calculation-profitability').css('display') == 'block');
  }

  //Функция для выбора рабочих постов в таблице
  function workzoneSelect(element) {
    dataTransmission(element);
  }

  //Функция для выбора работников в таблице
  function employeeSelect(element) {
    dataTransmission(element);
  }

  //Функция для блокировки строк в таблице
  function buttonUnlocked(element) {
    $(element).parent().parent().css({'background-color': '#f0f0f0'});
    $(element).hide();
    $(element).siblings().show();
    let dataRow = $(element).parent().parent().attr('data-tr-row');
    $('[data-row = "'+dataRow+'"]').each(function(i, element){
      if ($(element)[0].tagName != 'BUTTON') {
        $(element).attr('disabled', 'disabled');
      }
    });
    dataTransmission(element);
  }

  //Функция для разблокировки строк в таблице
  function buttonLock(element) {
    $(element).parent().parent().css({'background-color': 'transparent'});
    $(element).hide();
    $(element).siblings().show();
    let dataRow = $(element).parent().parent().attr('data-tr-row');
    $('[data-row = "'+dataRow+'"]').removeAttr("disabled");
    dataTransmission(element);
  }

  //Кнопка для добавления новой таблицы зонального наряда
  $('button.work_direction').click(function() {
    let valId = $('select.work_direction').val();

    if ($("div.dynamic-"+valId).css('display') == 'none') {
      $("div.dynamic-"+valId).css('display','block');
      alert("Таблица добавлена !");
    }
    else{
      alert("Таблица уже существует !");
    }

  });

  //Функция замены символа в строке
  function setCharAt(str,index,chr) {
    if(index > str.length-1) return str;
    return str.substr(0,index) + chr + str.substr(index+1);
  }

  //Функция для передачи данных таблицы в базу
   function dataTransmission(thisElem) {

    let valueArr = [];
    let dataRow = $(thisElem).attr('data-row');

    if (dataRow[1] == 1) {
      $('[data-row = "'+dataRow+'"]').each(function(i, element){

        if (i == 0) {valueArr.push({"d_table_workzone" : $(element).val()});}
        if (i == 1) {valueArr.push({"d_table_time_start" : $(element).val()});}
        if (i == 2) {valueArr.push({"d_table_time_finish" : $(element).val()});}
        if (i == 3) {valueArr.push({"d_table_responsible_officer" : $(element).val()});}
        if (i == 4) {valueArr.push({"d_table_list_completed_works" : $(element).val()});}
        if (i == 5) {valueArr.push({"d_table_quantity" : $(element).val()});}
        if (i == 6) {valueArr.push({"d_table_price" : $(element).val()});}
        if (i == 7) {valueArr.push({"d_table_currency" : $(element).val()});}

      });
      valueArr.push({"work_row_index" : dataRow});
    }

    if (dataRow[1] == 2){
      $('[data-row = "'+dataRow+'"]').each(function(i, element){

        if (i == 0) {valueArr.push({"d_table_spares_detail" : $(element).val()});}
        if (i == 1) {valueArr.push({"d_table_spares_vendor_code" : $(element).val()});}
        if (i == 2) {valueArr.push({"d_table_spares_unit_measurements" : $(element).val()});}
        if (i == 3) {valueArr.push({"d_table_spares_quantity" : $(element).val()});}
        if (i == 4) {valueArr.push({"d_table_spares_price" : $(element).val()});}
        if (i == 5) {valueArr.push({"d_table_spares_currency" : $(element).val()});}

      });
      valueArr.push({"spares_row_index" : dataRow});
    }

    valueArr.push({"number_sub_assignment" : 1});

    let workLocked = ($('[data-row="'+setCharAt(dataRow,1,'1')+'"]').attr('disabled') == 'disabled') ? 'active' : '';
    let sparesLocked = ($('[data-row="'+setCharAt(dataRow,1,'2')+'"]').attr('disabled') == 'disabled') ? 'active' : '';

    valueArr.push({"work_is_locked" : workLocked});
    valueArr.push({"spares_is_locked" : sparesLocked});

    for (var i = 0; i < workDirections.length; i++) {
      if (workDirections[i].id == dataRow[0]){
        valueArr.push({"d_table_work_direction" : workDirections[i].name});
        break;
      }
    }

    valueArr.push({"assignment_id" : assignmentId});

    $.ajax({
      url: "{{ route('add_new_sub_assignment') }}",
      type: "POST",
      data: {"_token": "{{ csrf_token() }}", valueArr:valueArr},
      headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        //console.log(data);
      },
      error: function (msg) {
        //alert('Ошибка admin');
      }
    });
  }

</script>

{{-- Модель + марка --}}

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

if ($("#mileageKM").val()) {
  var currentKilometers = $("#mileageKM").val();
  $("#mileageMiles").val(Math.round(currentKilometers/milesToKilometers));
}

{{-- При вводе миль --}}
$("#mileageMiles").change(function(){
    {{-- Получаем текущие мили --}}
    var currentMiles = $("#mileageMiles").val();
    //var currentMiles = $(Math.round("#mileageMiles").val());
    {{-- Задаём километры --}}
    $("#mileageKM").val(Math.round(currentMiles*milesToKilometers));
});


{{-- Подтягиваем список моделей по бренду --}}

var brandVal = "{{ $assignment->car_brand }}";
var modelVal = "{{ $assignment->car_model }}";

function brandInfo() {
    $('#carBrandInf').css('display', 'block').val(brandVal);
    var urlToFetch = "{{ url('admin/cars_in_service/api_models/') }}"+"/"+brandVal;
    $.get(urlToFetch, function(data){
            $("#carModelInf").empty();
            var modelsArray = JSON.parse(data);
            for(var modelIteration in modelsArray){
                $("#carModelInf").append('<option value="'+modelsArray[modelIteration]+'">'+modelsArray[modelIteration]+'</option>');
            }
    });
    $('#pCarBrandInf').css('display', 'none');
}

function modelInfo() {
    $('#carModelInf').css('display', 'block');
    var urlToFetch = "{{ url('admin/cars_in_service/api_models/') }}"+"/"+brandVal;
    $.get(urlToFetch, function(data){
            $("#carModelInf").empty();
            var modelsArray = JSON.parse(data);
            for(var modelIteration in modelsArray){
                $("#carModelInf").append('<option value="'+modelsArray[modelIteration]+'">'+modelsArray[modelIteration]+'</option>');
            }
    });
    $('#pCarModelInf').css('display', 'none');
}

    {{-- При изменении значения бренда --}}
    $("#carBrand").change(function(){
        {{-- Получаем название бренда и подтягиваем по API список моделей --}}
        var brandName = $("#carBrand").val();
        var urlToFetch = "{{ url('admin/cars_in_service/api_models/') }}"+"/"+brandName;
        $.get(urlToFetch, function(data){
            $("#carModel").empty();
            var modelsArray = JSON.parse(data);
            for(var modelIteration in modelsArray){
                $("#carModel").append('<option value="'+modelsArray[modelIteration]+'">'+modelsArray[modelIteration]+'</option>');
            }
        });
    });


    $("#carBrandInf").change(function(){
        var brandName = $("#carBrandInf").val();
        var urlToFetch = "{{ url('admin/cars_in_service/api_models/') }}"+"/"+brandName;
        $('#pCarBrandInf').text(brandName);
        $(this).css('display', 'none');
        $('#pCarBrandInf').css('display', 'block');
        brandVal = brandName;
        $.get(urlToFetch, function(data){
            $("#carModelInf").empty();
            var modelsArray = JSON.parse(data);
            for(var modelIteration in modelsArray){
                $("#carModelInf").append('<option value="'+modelsArray[modelIteration]+'">'+modelsArray[modelIteration]+'</option>');
            }
        });
    });


    $("#new-car").click(function(event){
      event.preventDefault();

      var brandName = $('#carBrandInf').val();
      var modelName = $('#carModelInf').val();

      if (!modelName) {
        modelName = modelVal;
        $('[name="car_model"]').val(modelName);
      }
      if (!brandName) {
        brandName = brandVal;
        $('[name="car_brand"]').val(brandName);
      }

      $('[name="car_general_name"]').val(brandName+' '+modelName);
      $(this).parent().submit();
    });

{{-- Конец работы с моделями--}}

{{-- Typeahead --}}

//console.log('ok');
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
  //console.log(states);

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

<script type="text/javascript">
var excelLink = "{{ url('admin/assignments/view/export') }}";
var assignmentId = "{{ $assignment->id }}";
  // Скачивание XLS
  function downloadXls() {
    $('#download_xls').toggle();
  }

  $('#download_xls').change(function() {
    const docName = $(this).val();
    if (docName !== '') {
      //console.log(excelLink + '/' + docName + '/' + assignmentId);
      document.location.href = excelLink + '/' + docName + '/' + assignmentId;
    }
  });

</script>

@endsection
