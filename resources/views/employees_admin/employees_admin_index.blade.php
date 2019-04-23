@extends('layouts.limitless')

@section('page_name')
Сотрудники
    <a href="{{ url('/supervisor/add_employee') }}" class="btn btn-primary">Добавить сотрудника</a>    
    <a href="{{ url('/supervisor/employee_archive') }}" class="btn btn-secondary">Архив сотрудников</a>
@endsection

@section('content')
    <table class="table">
        @foreach($employee_data as $employee)
            <tr>
                <td>
                    <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                        <div class="btn btn-success">
                            Редактировать сотрудника
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
