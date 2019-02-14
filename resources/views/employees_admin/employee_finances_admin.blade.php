@extends('layouts.limitless')

@section('page_name')
Финансы по сотруднику: {{ $employee->general_name }}
{{-- Вернуться : Кнопка --}}
    <a href="{{ url('view_employees') }}">
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

        {{-- Изменить ставку : модальное окно --}}
        <div class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-top: 10px">
            Изменить ставку
        </div>

    <hr>
    {{-- Начисления : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/credit/'.$employee->id) }}">
        <div class="btn btn-success">
            Начисления
        </div>
    </a>
    {{-- Выплата : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/payout/'.$employee->id) }}">
        <div class="btn btn-warning">
            Выплата
        </div>
    </a>

    
    {{-- Штрафы : Кнопка --}}
    <a href="{{ url('/supervisor/employee_fines/'.$employee->id ) }}">
        <div class="btn btn-secondary">
            Штрафы сотрудинка
        </div>
    </a>
    
    {{-- Жетоны на кофе : Кнопка --}}
    <a href="{{ url('/supervisor/employee_coffee_tokens/'.$employee->id ) }}">
        <div class="btn btn-info">
            Жетоны на кофе
        </div>
    </a>

    <hr>

    {{-- Модальное окно : изменить ставку --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
            {{-- Форма изменения ставки --}}
                <form action="{{ url('/admin/employee_finances/change_standard_shift_wage') }}" method="POST">
                    @csrf
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Изменить ставку за смену</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- ID сотрудника --}}
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        
                        {{-- Вывод данных по текущей ставке --}}
                        <b>Текущая ставка:</b> {{ $employee->standard_shift_wage }}

                        {{-- Новая ставка --}}
                        <div class="form-group">
                            <label>Новая ставка</label>
                            <input class="form-control" name="new_wage">
                        </div>
                    </div>
                    <div class="modal-footer">

                        {{-- Кнопка закрыть --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Выйти</button>
                        
                        {{-- Кнопка "сохранить" --}}
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        
                    </div>
                </form>
                {{-- Конец формы изменения ставки --}}
            </div>
            
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <b>Последние начисления:</b>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                    </tr>
                </thead>
                @foreach($balance_logs as $balance_logs )
                <tr>
                    <td>
                    {{ $balance_logs->date }}
                    </td>
                    <td>
                    {{ $balance_logs->amount }}
                    </td>
                    <td>

                    </td>
                    <td>
      
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="form-group col-md-6">
            <b>Последние выплаты:</b>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                    </tr>
                </thead>
                @foreach ($payout_logs as $payout_log )
                <tr>
                    <td>
                    {{ $payout_log->date }}
                    </td>
                    <td>
                    {{ $payout_log->amount }}
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <b>Последние штрафы:</b>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                        <th>Статус</th>
                        <th>Основание</th>
                    </tr>
                </thead>
                @foreach($employee_fines as $employee_fines)
                <tr>
                    <td>
                        {{ $employee_fines->date }}
                    </td>
                    <td>
                        -{{ $employee_fines->amount }}
                    </td>
                    <td>
                        {{ $employee_fines->old_balance }}
                    </td>
                    <td>
                        {{ $employee_fines->status }}
                    </td>
                    <td>
                        {{ $employee_fines->reason }}
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
                        <th>Остаток</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                @foreach($token_logs as  $token_logs)
                <tr>
                    <td>
                    {{ $token_logs->token_count}}
                    </td>
                    <td>
                    -{{ $token_logs->token_count*5}}
                    </td>
                    <td>
                    {{ $token_logs->old_balance }}
                    </td>
                    <td>
                    {{ $token_logs->date }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>


@endsection