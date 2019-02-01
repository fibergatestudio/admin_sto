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
        <input type="number" name="balance" min="1">

        <button type="submit" class="btn btn-success">Пополнить</button>

    </form>
    <hr>
    {{-- История начислений (последние 10) --}}
    <h4>История начислений (последние 10 операций)</h4>
    <table class="table">
        <thead>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Тип добавления</th>
            <th>Причина</th>
        </thead>
        <tbody>
            @foreach($balance as $employee_balance_entry)
                <tr>
                    <td>{{ $employee_balance_entry->amount}}</td>
                    <td>{{ $employee_balance_entry->date}}</td>
                    <td>{{ $employee_balance_entry->action}}</td>
                    <td>{{ $employee_balance_entry->reason}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
