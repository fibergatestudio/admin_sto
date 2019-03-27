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
    {{-- Наряды сотрудника --}}
    <b>Тест вывода заходов по нарядам сотрудника:</b><br>    
    <table class="table">
                <thead>
                    <tr>
                        <th>Номер наряда</th>
                        <th>Основание</th>
                        <th>Описание</th>
                        <th>Сумма</th>
                        <th>Валюта</th>
                    </tr>
                </thead>
                @foreach ($test as $test)
                <tr>
                    <td>
                        {{$test->id}}
                    </td>
                    <td>
                        {{$test->as_bas}}
                    </td>
                    <td>
                        {{$test->as_des}}
                    </td>
                    <td>
                        {{$test->as_inc}}
                    </td>
                    <td>
                        {{$test->as_cur}}
                    </td>
                </tr>
                @endforeach
            </table>

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

    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">История Финансов:</h3>
            </div>
        <form action="{{ url('/supervisor/employee_finances/'.$employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="employee_id" value="$employee->id">
            <div class="row">
                <div class="col">
                    <div class="custom-control">
                        <label>Фильтры типа: </label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="filter_income" value="1" class="custom-control-input" id="check1" 
                        @if ($view_income == 'null')
                        
                        @else
                            checked
                        @endif
                        >
                        <label class="custom-control-label" for="check1">Начисления</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="filter_payout" value="2" class="custom-control-input" id="check2"
                        @if ($view_payout == 'null')
                        
                        @else
                            checked
                        @endif
                        >
                        <label class="custom-control-label" for="check2">Выплаты</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="filter_fine" value="3" class="custom-control-input" id="check3"
                        @if ($view_fine == 'null')
                        
                        @else
                            checked
                        @endif
                        >
                        <label class="custom-control-label" for="check3">Штрафы</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="filter_coffee" value="4" class="custom-control-input" id="check4"
                        @if ($view_coffee == 'null')
                        
                        @else
                            checked
                        @endif
                        >
                        <label class="custom-control-label" for="check4">Кофе</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control">
                        <button type="sumbit" class="btn btn-primary">Применить</button>
                    </div>
                </div>
            </div>
        </form>
        
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Тип</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                        <th>Основание</th>
                        <th>Статус</th>
                    </tr>
                </thead>

                @if ($view_payout == 'null')

                @else

                    {{-- Вывод выплат --}}
                    @foreach ($view_payout as $view_payout)
                    <tr>
                        <td>
                            {{ $view_payout->date }}
                        </td>
                        <td>
                            {{ $view_payout->type }}
                        </td>
                        <td>
                            -{{ $view_payout->amount }}
                        </td>
                        <td>
                            {{ $view_payout->old_balance }}
                        </td>
                        <td>
                            {{ $view_payout->reason }}
                        </td>
                        <td>
                            {{ $view_payout->status }}
                        </td>
                    </tr>
                    @endforeach

                @endif

                @if ($view_income == 'null')
                
                @else

                    {{-- Вывод заходы --}}
                    @foreach ($view_income as $view_income)
                    <tr>
                        <td>
                            {{ $view_income->date }}
                        </td>
                        <td>
                            {{ $view_income->type }}
                        </td>
                        <td>
                            -{{ $view_income->amount }}
                        </td>
                        <td>
                            {{ $view_income->old_balance }}
                        </td>
                        <td>
                            {{ $view_income->reason }}
                        </td>
                        <td>
                            {{ $view_income->status }}
                        </td>
                    </tr>
                    @endforeach

                @endif

                @if($view_fine == 'null')

                @else

                    {{-- Вывод штрафы --}}
                    @foreach ($view_fine as $view_fine)
                    <tr>
                        <td>
                            {{ $view_fine->date }}
                        </td>
                        <td>
                            {{ $view_fine->type }}
                        </td>
                        <td>
                            -{{ $view_fine->amount }}
                        </td>
                        <td>
                            {{ $view_fine->old_balance }}
                        </td>
                        <td>
                            {{ $view_fine->reason }}
                        </td>
                        <td>
                            {{ $view_fine->status }}
                        </td>
                    </tr>
                    @endforeach

                @endif
                
                @if ($view_coffee == 'null')

                @else

                {{-- Вывод штрафы --}}
                @foreach ($view_coffee as $view_coffee)
                <tr>
                    <td>
                        {{ $view_coffee->date }}
                    </td>
                    <td>
                        {{ $view_coffee->type }}
                    </td>
                    <td>
                        -{{ $view_coffee->amount }}
                    </td>
                    <td>
                        {{ $view_coffee->old_balance }}
                    </td>
                    <td>
                        {{ $view_coffee->reason }}
                    </td>
                    <td>
                        {{ $view_coffee->status }}
                    </td>
                </tr>
                @endforeach

                @endif
                
               
            </table>
        </div>
    </div>


@endsection