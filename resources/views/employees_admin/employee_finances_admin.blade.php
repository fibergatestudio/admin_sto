@extends('layouts.limitless')

@section('page_name')
Финансы по сотруднику: {{ $employee->general_name }}
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
    
    {{-- Штрафы : Кнопка --}}
    <a href="{{ url('/supervisor/employee_fines/'.$employee->id ) }}">
        <div class="btn btn-secondary">
            Штрафы сотрудинка
        </div>
    </a>
    
    {{-- Жетоны на кофе : Кнопка --}}
    <a href="{{ url('/supervisor/employee_coffee_tokens/'.$employee->id ) }}">
        <div class="btn btn-light">
            Жетоны на кофе
        </div>
    </a>

    <hr>
    {{-- Вернуться : Кнопка --}}
    <a href="{{ url('view_employees') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>

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
                        <button type="button" class="btn btn-primary">Сохранить</button>
                        
                    </div>
                </form>
                {{-- Конец формы изменения ставки --}}
            </div>
            
        </div>
    </div>


@endsection