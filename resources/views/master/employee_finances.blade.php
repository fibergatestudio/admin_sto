@extends('layouts.limitless')

@section('page_name')
    Финансы по сотруднику: {{ $employee->general_name }}
    {{-- Вернуться : Кнопка --}}
    <a href="{{ url('/master/employees/profiles') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection
@section('content')
    {{-- Баланс сотрудника --}}
    <b>Баланс сотрудника:</b> {{ $employee->balance }}<br>
    <hr>
    {{-- Ставка сотрудника за смену --}}
    <b>Ставка сотрудника за смену:</b> {{ $employee->standard_shift_wage }}<br>
    <hr>

    <div class="form-row">
        <div class="form-group col-md-6">
            <b>Последние штрафы:</b>
            <table class="table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Основание</th>
                </tr>
                </thead>
                @foreach($employee_fines as $employee_fine)
                    <tr>
                        <td>
                            {{ $employee_fine->date }}
                        </td>
                        <td>
                            {{ $employee_fine->amount }}
                        </td>
                        <td>
                            {{ $employee_fine->status }}
                        </td>
                        <td>
                            {{ $employee_fine->reason }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="form-group col-md-6">
            <b>Жетоны:</b>
            <table class="table">
                <thead>
                <tr>
                    <th>Кол-во жетонов</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection