@extends('layouts.limitless')
@section('page_name')
    Профили рабочих
@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Ответственный работник</th>
            <th>Статус</th>
            <th>Смена заработной платы</th>
            <th>Баланс</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        @foreach ($employees as $employee)
            <tr>
                <td>{{($employee->general_name)}} {{-- Ответственный работник --}}</td>
                <td>{{($employee->status)}} {{--  Статус --}}</td>
                <td>{{($employee->standard_shift_wage)}} {{-- Смена заработной платы --}}</td>
                <td>{{($employee->balance)}} {{-- Баланс --}}</td>
                <td>
                    <a href="{{ url('/master/assignments/'.$employee->id) }}">
                        <div class="btn btn-secondary">
                            Наряды работника
                        </div>
                    </a>
                </td>
                <td></td>
            </tr>
        @endforeach



    </table>
@endsection
