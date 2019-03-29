@extends('layouts.limitless')

@section('page_name')
    Сотрудники
@endsection

@section('content')
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
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                        <div class="btn btn-success">
                            Редактировать сотрудника
                        </div>
                    </a>
                </td>
                {{-- Кнопка редактирования сотрудника --}}
                <td>
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id) }}">
                        <div class="btn btn-secondary">
                            Статус сотрудника
                        </div>
                    </a>
                </td>

                <td>

                        <a class="btn btn-secondary" href="{{ url('/supervisor/add_documents/'.$employee->id) }}">Добавить документы</a>

                        <a class="btn btn-secondary" href="{{ url('/documents/'.$employee->id) }}">Посмотреть документы</a>

                </td>
            </tr>
        @endforeach
    </table>

    <a href="{{ url('/supervisor/add_employee') }}">
        <div class="btn btn-primary">
            Добавить сотрудника
        </div>
    </a>
    <hr>
    <a href="{{ url('/supervisor/employee_archive') }}">
        <div class="btn btn-secondary">
            Архив сотрудников
        </div>
    </a>




@endsection
