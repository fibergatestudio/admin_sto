@extends('layouts.limitless')

@section('page_name')
Финансы по сотруднику: {{ $employee->general_name }}
@endsection

@section('content')
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

    <hr>
    <a href="{{ url('view_employees') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>

@endsection