@extends('layouts.limitless')

@section('page_name')
Настройка уведомлений
@endsection


@section('content')

@if ($exist == '0')

<p>Файлы настроек на созданы, нажмите "создать"</p>
<a href="{{ url('/employee/notification/create_settings') }}"><button class="btn btn-success">Создать</button></a>

@elseif ($exist == '1')
<div>
        <div class="row">

            <form class="border border-light p-5" action="{{ url('/employee/notification/change_rules') }}" method="POST">
            @csrf
            <p class="h4 mb-4">Какие уведомления присылать</p>


            <div id="checked" class="d-flex justify-content-around">
                <div style="text-align: left;">
                    <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="emptg_assignment_notification" value="1" class="custom-control-input" id="first" 
                            @if($employee_options->tg_assignment_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="first">Новый наряд</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_income_notification" type="checkbox" value="1" class="custom-control-input" id="second"
                            @if($employee_options->tg_income_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="second">Новый приход</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_expense_notification" type="checkbox" value="1" class="custom-control-input" id="third"
                            @if($employee_options->tg_expense_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="third">Новый расход</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_fine_notification" type="checkbox" value="1" class="custom-control-input" id="four"
                            @if($employee_options->tg_fine_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="four">Штраф</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_bonus_notification" type="checkbox" value="1" class="custom-control-input" id="five"
                            @if($employee_options->tg_bonus_notification== '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="five">Премия</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_supply_order_notification" type="checkbox" value="1" class="custom-control-input" id="six"
                            @if($employee_options->tg_supply_order_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="six">Обновление статуса заказа</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="emptg_client_master_notification" type="checkbox" value="1" class="custom-control-input" id="seven"
                            @if($employee_options->tg_client_master_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="seven">Запись к мастеру</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-info btn-block my-4" type="submit">Изменить</button>

            </form>
                   
            
        </div>
    </div>
@endif


@endsection