@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Сотрудники</h2>
    <table class="table">
        @foreach($employee_data as $employee)
            <tr>
                <td>
                    {{ $employee->general_name }}
                </td>

                <td>
                    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id) }}">
                        <div class="btn btn-secondary">
                            Статус сотрудника
                        </div>
                    </a>
                </td>

            </tr>
        @endforeach
    </table>

    <a href="{{ url('/add_employee') }}">
        <div class="btn btn-primary">
            Добавить сотрудника
        </div>
    </a>
    <hr>
    <a href="{{ url('admin/employee_archive') }}">
        <div class="btn btn-secondary">
            Архив сотрудников
        </div>
    </a>

@endsection