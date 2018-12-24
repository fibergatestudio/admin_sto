@extends('layouts.limitless')

@section('page_name')
Финансовый сектор
@endsection

@section('content')
    @foreach($employee_data as $employee)
        {{ $employee->general_name }}

        <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
            <div class="btn btn-primary">
                Финансы сотрудника
            </div>
        </a>

    @endforeach
@endsection