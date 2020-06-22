@extends('layouts.limitless')

@section('page_name')
    <span class="mr-2">Финансы по сотруднику:</span> {{ $employee->general_name }}
{{-- Вернуться : Кнопка --}}
    <a class="mx-1" href="{{ url('/supervisor/view_employees') }}" title="Страница Содрудники">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
{{-- Начисления : Кнопка --}}
    <a class="mx-1" href="{{ url('/supervisor/employee_finances/credit/'.$employee->id) }}">
        <div class="btn btn-success">
            Начисления
        </div>
    </a>
    {{-- Выплата : Кнопка --}}
    <a class="mx-1" href="{{ url('/supervisor/employee_finances/payout/'.$employee->id) }}">
        <div class="btn btn-warning">
            Выплата
        </div>
    </a>


    {{-- Штрафы : Кнопка --}}
    <a class="mx-1" href="{{ url('/supervisor/employee_fines/'.$employee->id ) }}">
        <div class="btn btn-secondary">
            Штрафы сотрудинка
        </div>
    </a>

    {{-- Жетоны на кофе : Кнопка --}}
    <a class="mx-1" href="{{ url('/supervisor/employee_coffee_tokens/'.$employee->id ) }}">
        <div class="btn btn-info">
            Жетоны на кофе
        </div>
    </a>
@endsection

@section('content')
    
    {{-- Баланс сотрудника --}}
    <b class="mr-2">Баланс сотрудника:</b>    <div class="btn btn-success">{{ $employee->balance }} MDL</div><br>
    
    <hr>
    {{-- Наряды сотрудника --}}
    <b>Тест вывода заходов по нарядам сотрудника:</b><br>
    <div class="mt-3 card card-p">
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
    </div>

    {{-- Ставка сотрудника за смену --}}
    <b>Ставка сотрудника за смену:</b> {{ $employee->pay_per_shift }}<br>

        {{-- Изменить ставку : модальное окно --}}
        <div class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-top: 10px">
            Изменить ставку
        </div> 

    <hr>

    {{-- Модальное окно : изменить ставку --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
            {{-- Форма изменения ставки --}}
                <form action="{{ url('/admin/employee_finances/change_standard_shift_wage') }}" method="POST">
                    @csrf
                    
                    <div class="modal-header">
                        <h5 class="text-center modal-title" id="exampleModalLabel">Изменить ставку за смену</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- ID сотрудника --}}
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        
                        {{-- Вывод данных по текущей ставке --}}
                        <b>Текущая ставка:</b> {{ $employee->pay_per_shift }}

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

    <div id="finances" class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">История Финансов:</h3>
            </div>
            <div class="row align-items-center">
                <div class="col">
                    <div class="custom-control">
                        <label>Фильтры типа: </label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" class="custom-control-input" id="check1" v-model="income">
                        <label class="custom-control-label" for="check1">Начисления</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="2" class="custom-control-input" id="check2" v-model="payout">
                        <label class="custom-control-label" for="check2">Выплаты</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="3" class="custom-control-input" id="check3" v-model="fine">
                        <label class="custom-control-label" for="check3">Штрафы</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="4" class="custom-control-input" id="check4" v-model="coffee">
                        <label class="custom-control-label" for="check4">Кофе</label>
                    </div>
                </div>
                <div class="col">
                    <div v-if="sort">
                        <div v-on:click="sort = !sort"  class="btn btn-primary">Сорт.Новые</div>
                    </div>
                    <div v-if="!sort">
                        <div v-on:click="sort = !sort"  class="btn btn-info">Сорт.Старые</div>
                    </div>
                </div>
            </div>
        </form>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Тип</th>
                        <th>Сумма</th>
                        <th>Было на балансе</th>
                        <th>Основание</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody v-if="!sort" id="tablecontents">        
                    @foreach($all_logs_asc as $all_logs_asc_entry)
                        <tr v-if="{{ $all_logs_asc_entry->eng_type }}">
                                <td>
                                    {{ $all_logs_asc_entry->date }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->type }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->amount }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->old_balance }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->reason }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->status }}
                                </td>
                            </tr>
                    @endforeach         
                </tbody>
                <tbody v-if="sort" id="tablecontents">
                @foreach($all_logs_desc as $all_logs_desc_entry)
                        <tr v-if="{{ $all_logs_desc_entry->eng_type }}">
                                <td>
                                    {{ $all_logs_desc_entry->date }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->type }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->amount }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->old_balance }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->reason }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->status }}
                                </td>
                            </tr>
                    @endforeach         
                </tbody>
            </table>
        </div>
    </div>

<script>
    var finances = new Vue({
        el: '#finances',
        data: {
            income: 'true',
            payout: 'true',
            fine: 'true',
            coffee: 'true',
            sort: false
        }
    
    });
</script>


@endsection