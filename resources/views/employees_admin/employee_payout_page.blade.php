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
    <table class="table">
        <thead>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Тип добавления</th>
            <th>Причина</th>
        </thead>
        <tbody>
            @foreach($employee_payout as $employee_payout)
                <tr>
                    <td>{{ $employee_payout->amount}}</td>
                    <td>{{ $employee_payout->date}}</td>
                    <td>{{ $employee_payout->action}}</td>
                    <td>{{ $employee_payout->reason}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection