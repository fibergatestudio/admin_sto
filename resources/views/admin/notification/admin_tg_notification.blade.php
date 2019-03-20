@extends('layouts.limitless')

@section('page_name')
Настройка уведомлений
@endsection


@section('content')


@if ($exist == '0')

<p>Файлы настроек на созданы, нажмите "создать"</p>
<a href="{{ url('/admin/notification/create_settings') }}"><button class="btn btn-success">Создать</button></a>

@elseif ($exist == '1')
    <div>
        <div class="row">

            <form class="border border-light p-5" action="{{ url('/admin/notification/change_rules') }}" method="POST">
            @csrf
            <p class="h4 mb-4">Какие уведомления присылать</p>


            <div id="checked" class="d-flex justify-content-around">
                <div style="text-align: left;">
                    <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="tg_assignment_notification" value="1" class="custom-control-input" id="first" 
                            @if($user_options->tg_assignment_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="first">Новый наряд</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="tg_income_notification" type="checkbox" value="1" class="custom-control-input" id="second"
                            @if($user_options->tg_income_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="second">Новый приход</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                            <input name="tg_expense_notification" type="checkbox" value="1" class="custom-control-input" id="third"
                            @if($user_options->tg_expense_notification == '0')

                            @else
                                checked
                            @endif
                            >
                        <label class="custom-control-label" for="third">Новый расход</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-info btn-block my-4" type="submit">Изменить</button>

            </form>
                   
            
        </div>
    </div>
@endif

@endsection