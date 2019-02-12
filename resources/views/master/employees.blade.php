@extends('layouts.limitless')
@section('page_name')
    Профили рабочих
@endsection
@section('content')
    <table class="table">
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
            <th></th>
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

                    @if($employee->status == 'confirmed')
                    <td>
                        <b class="badge bg-success" type="text">{{ $record->status }}</b><br>
                    </td>
                    @endif

                <td>
                    <a href="{{ url('/master/confirm_employee/'.$employee->id) }}">
                        <div class="btn btn-warning">
                            Подтвердить запись
                        </div>
                    </a>
                </td>
                <td>
                    <a href="{{ url('/master/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
