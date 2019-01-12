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
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id) }}">
                        <div class="btn btn-secondary">
                            Статус сотрудника
                        </div>
                    </a>
                </td>

                <td>
                    @if(!isset($employee->link_scan))
                        <a class="btn btn-secondary" href="{{ url('/add_passport_scan/'.$employee->id) }}">Добавить скан паспорта</a>
                    @else
                        <img src="{{$employee->link_scan}}" alt="" width="100px" height="100px">
                    @endif
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
    
    <a class="btn btn-secondary" href="{{ url('/add_passport_scan/'.$employee->id) }}">Сканы паспортов</a>
    

@endsection