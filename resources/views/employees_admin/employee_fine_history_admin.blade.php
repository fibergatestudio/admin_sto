{{-- Страница ИСТОРИИ ШТРАФОВ по сотруднику --}}

@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Финансовый сектор</h2>
    @foreach($employee_data as $employee)
        {{ $employee->general_name }}

        <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
            <div class="btn btn-primary">
                Финансы сотрудника
            </div>
        </a>

    @endforeach
@endsection