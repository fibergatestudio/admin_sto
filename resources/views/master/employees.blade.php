@extends('layouts.limitless')
@section('page_name')
    Профили рабочих
@endsection
@section('content')
    <div class="card card-p">
        <table class="table-list-profile table">
            <thead>
            <tr>
                <th>Сотрудник</th>
                <th>Дата принятия</th>
                <th>ФИО</th>
                <th>Номер паспорта</th>
                <th>Телефон</th>
                <th>Резервный телефон</th>
                <th>Работает с</th>
                <th>Работает по</th>
                <th>Финансы</th>
                <th></th>
            </tr>
            </thead>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{($employee->general_name)}} {{-- Ответственный работник --}}</td>
                    <td>{{($employee->date_join)}} {{--  Статус --}}</td>
                    <td>{{($employee->fio)}} {{--  Статус --}}</td>
                    <td>{{($employee->passport)}} {{--  Статус --}}</td>
                    <td>{{($employee->phone)}} {{--  Статус --}}</td>
                    <td>{{($employee->reserve_phone)}} {{--  Статус --}}</td>
                    <td>{{($employee->hour_from)}} {{--  Статус --}}</td>
                    <td>{{($employee->hour_to)}} {{--  Статус --}}</td>
                    <td>
                        <a href="{{ url('/master/employees/employee_finances/'.$employee->id) }}">
                            <div class="btn btn-primary">
                                Финансы
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection
