{{-- Общая страница ФИНАНСОВ по сотруднику --}}

@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Финансы по сотруднику: {{ $employee->general_name }}</h2>
    Баланс сотрудника: {{ $balance }}<br>
    <hr>
    {{-- Начисления --}}
    <a href="{{ url('/supervisor/employee_finances/credit/'.$employee->id) }}">
        <div class="btn btn-success">
            Начисления
        </div>
    </a>
    
    {{-- Штрафы --}}
    <a href="{{ url('/supervisor/employee_fines/'.$employee->id ) }}">
        <div class="btn btn-secondary">
            Штрафы сотрудинка
        </div>
    </a>
    
    {{-- Жетоны на кофе --}}
    <a href="{{ url('/supervisor/employee_coffee_tokens/'.$employee->id ) }}">
        <div class="btn btn-light">
            Жетоны на кофе
        </div>
    </a>

@endsection