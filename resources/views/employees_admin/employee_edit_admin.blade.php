@extends('layouts.limitless')

@section('page_name')
    Редактирование сотрудника: {{ $employee_edit->general_name }}
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Сотрудник</th>
                <th>Дата принятия</th>
                <th>ФИО</th>
                <th>Номер Паспорта</th>
                <th>ИН</th>
                <th>Резервный телефон</th>
                <th>Работает С</th>
                <th>По</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tr>
            <td>
                {{ $employee_edit->general_name }}
            </td>
            <td>
                {{ $employee_edit->date_join }}
            </td>
            <td>
                {{ $employee_edit->fio }}
            </td>
            <td>
                {{ $employee_edit->passport }}
            </td>
            <td>
                {{ $employee_edit->id_code }}
            </td>
            <td>
                {{ $employee_edit->reserve_phone }}
            </td>
            <td>
                {{ $employee_edit->hour_from }}
            </td>
            <td>
                {{ $employee_edit->hour_to }}
            </td>
        </tr>
    </table>

    {{-- Форма изменения данных работника --}}
        <h2>Форма изменения данных</h2>

        <form action="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit/apply_employee_edit') }}" method="POST" enctype="multipart/form-data">
            @csrf

                <hr>

                <input type="hidden" name="id" value="{{ $employee->id }}">

                {{-- Прикрепить скан паспорта --}}
                <div class="form-group">
                    <label>Скан паспорта</label>
                    <input type="file" name="scan_doc">
                </div>

                <div class="form-row">

                    {{-- Дата принятия на работу --}}
                    <div class="form-group col-md-6" >
                        <label>Дата принятия на работу</label>
                        <input type="date" name="date_join"  value="2019-01-22" min="2000-01-01" max="2019-12-31" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>ФИО</label>
                        <input type="text" name="fio" id="carBrand" class="form-control typeahead">
                    </div>

                </div>

                <div class="form-row">        

                    <div class="form-group col-md-6">
                        <label>Серия и номер паспорта</label>
                        <input type="text" name="passport" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Идентификационный код</label>
                        <input type="text" name="id_code" class="form-control">
                    </div>

                </div>
                
                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>Баланс</label>
                        <input type="number" name="balance" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Резервный телефон</label>
                        <input type="tel"  name="reserve_phone" class="form-control typeahead">
                    </div>
                </div>

                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>С какого часа</label>
                        <input type="time" name="hour_from" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        <label>По какой час</label>
                        <input type="time" name="hour_to" class="form-control typeahead">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    Применить
                </button>
                
            </form>
@endsection