@extends('layouts.limitless')
@section('page_name')
    Профили рабочих
@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Ответственный работник</th>
            <th>Статус сотрудника</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        @foreach ($employees as $employee)
            <tr>
                <td>{{($employee->general_name)}} {{-- Ответственный работник --}}</td>
                <td>{{($employee->status)}} {{--  Статус --}}</td>
                <td>
                    <a href="{{ url('/master/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
