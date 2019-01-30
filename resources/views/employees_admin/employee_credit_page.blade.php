@extends('layouts.limitless')

@section('page_name')
Страница начислений по сотруднику: {{ $employee->name }}
@endsection

@section('content')
    {{-- Форма начислить вручную --}}
    <h4>Пополнить вручную</h4>
    <form action="{{ url('/supervisor/employee_finances/credit/'.$employee->id.'/add_balance') }}" method="POST">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <input type="text" name="balance" min="1">

        <button type="submit" class="btn btn-success">Пополнить</button>
        
    </form>
    <hr>
    {{-- История начислений (последние 10) --}}
    <h4>История начислений (последние 10 операций)</h4>
    <p>Баланс: {{ $employee->balance }}</p> {{-- Для теста --}}
@endsection