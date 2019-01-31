@extends('layouts.limitless')

@section('page_name')
Страница выплат по сотруднику: {{ $employee->name }}
@endsection

@section('content')
    {{-- Форма выплатить вручную --}}
    <h4>Выплатить вручную</h4>
    <form action="{{ url('/supervisor/employee_finances/payout/'.$employee->id.'/apply_payout') }}" method="POST">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <input type="number" name="payout_balance" min="1">

        <button type="submit" class="btn btn-success">Выплатить</button>
        
    </form>
    <hr>
    {{-- История выплат (последние 10) --}}
    <h4>История выплат (последние 10 операций)</h4>
    <p>Баланс: {{ $employee->balance }}</p> {{-- Для теста --}}

@endsection