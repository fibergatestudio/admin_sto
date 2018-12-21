{{-- employee_credit_page --}}

@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Страница начислений по сотруднику: {{ $employee->name }}</h2>
    {{-- Форма начислить вручную --}}
    <h4>Пополнить вручную</h4>
    <form>
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <input type="number" name="credit_amount" min="1">

        <button type="submit" class="btn btn-success">Пополнить</button>
        
    </form>
    <hr>
    {{-- История начислений (последние 10) --}}
    <h4>История начислений (последние 10 операций)</h4>

    {{--<div class="employee_balances">--}}
        {{--@if ( $employeeBalances->count() )--}}
            {{--@foreach( $employeeBalances as $employee )--}}
                {{--<div class="employee_balances_item" data-id="{{ $employee->id }}">--}}
                    {{--<div class="employee_balances_item-balance">{{ $employee->balance }}</div>--}}
                    {{--<div class="employee_item-edit action"--}}
                         {{--data-event="edit" data-id="{{ $employee->id }}">--}}
                        {{--<img src="/svg/edit.svg" alt="Edit">--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--@endif--}}
    {{--</div>--}}

@endsection