@extends('layouts.limitless')

@section('page_name')
Страница выплат по сотруднику: {{ $employee->name }}

{{-- Вернуться : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
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
    <div class="row">
        <h4>Текущий баланс:&nbsp;</h4>
        <b style="font-size: 16px;" class="btn btn-success"> {{ $employee->balance}}</b>
    </div>
    <hr>
    {{-- История выплат (последние 10) --}}
    <h4>История выплат (последние 10 операций)</h4>
    <table class="table">
        <thead>
            <th>Сумма</th>
            <th>Остаток</th>
            <th>Дата</th>
            <th>Причина</th>
        </thead>
        <tbody>
            @foreach($employee_payout as $employee_payout)
                <tr>
                    <td>{{ $employee_payout->amount}}</td>
                    <td>{{ $employee_payout->old_balance}}</td>
                    <td>{{ $employee_payout->date}}</td>
                    <td>{{ $employee_payout->reason}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection