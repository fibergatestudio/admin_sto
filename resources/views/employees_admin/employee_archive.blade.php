@extends('layouts.limitless')

@section('page_name')
    Архив сотрудников
@endsection

@section('content')
    @foreach($archived_employees as $archived_employee)
        {{ $archived_employee->general_name }}
    @endforeach

    <table style="display: inline-table;" class="table mt-3 card card-p">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Баланс</th>
                    <th></th>{{-- Кнопки управления --}}
                </tr>
            </thead>
            <tbody>
            @foreach($archived_employees as $employee)
                <tr>
                    <td>
                        <!-- <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a> -->
                        {{ $employee->general_name }}   
                    </td>

                    <td>
                        {{ $employee->balance }} MDL
                    </td>

                    <td>
                        <a href="{{ url('/supervisor/employee_archive/restore/'.$employee->id) }}">
                            <div class="btn btn-primary">
                                Восстановить
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
    </table>
    <hr>
    <a href="{{ url('/supervisor/view_employees') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection