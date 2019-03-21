@extends('layouts.limitless')

@section('page_name')
    Редактирование сотрудника: {{ $employee->general_name }}
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Сотрудник</th>
                <th>Дата принятия</th>
                <th>ФИО</th>
                <th>День Рождения</th>
                <th>Номер Паспорта</th>
                <th>Баланс</th>
                <th>Телефон</th>
                <th>Резервный телефон</th>
                <th>Работает С</th>
                <th>По</th>
                <th>Телеграм ID</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tr>
            <td>
                {{ $employee->general_name }}
            </td>
            <td>
                {{ $employee->date_join }}
            </td>
            <td>
                {{ $employee->fio }}
            </td>
            <td>
                {{ $employee->birthday }} {{-- День рождения --}}
            </td>
            <td>
                {{ $employee->passport }}
            </td>
            <td>
                {{ $employee->balance }}
            </td>
            <td>
                {{ $employee->phone }}
            </td>
            <td>
                {{ $employee->reserve_phone }}
            </td>
            <td>
                {{ $employee->hour_from }}
            </td>
            <td>
                {{ $employee->hour_to }}
            </td>
            <td>
                {{ $employee->telegram_id }}
            </td>
        </tr>
    </table>

    {{-- Форма изменения данных работника --}}
        <h2>Форма изменения данных</h2>

        <form action="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit/apply_employee_edit') }}" method="POST" enctype="multipart/form-data">
            @csrf

                <hr>

                <input type="hidden" name="id" value="{{ $employee->id }}">

                {{-- Прикрепить скан паспорта --}}
                <!-- <div class="form-group">
                    <label>Скан паспорта</label>
                    <input type="file" name="scan_doc">
                </div> -->

                <div class="form-row">

                    {{-- Дата принятия на работу --}}
                   <!-- <div class="form-group col-md-5" >
                        <label>Дата принятия на работу</label>
                        <input type="date" name="date_join"  value="{{ $employee->date_join }}" min="2000-01-01" max="2019-12-31" class="form-control">
                    </div> -->

                    <div class="form-group col-md-6">
                        <label>ФИО</label>
                        <input type="text" name="fio" value="{{ $employee->fio }}" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-2">
                        <label>День Рождения</label>
                        <input type="date" name="birthday" value="{{ $employee->birthday }}" class="form-control typeahead">
                    </div>

                </div>

                <div class="form-row">        

                    <div class="form-group col-md-6">
                        <label>Серия и номер паспорта</label>
                        <input type="text" name="passport" value="{{ $employee->passport }}" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Телефон</label>
                        <input type="text" name="phone" value="{{ $employee->phone }}" class="form-control">
                    </div>

                </div>
                
                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>Баланс</label>
                        <input type="number" name="balance" value="{{ $employee->balance }}" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Резервный телефон</label>
                        <input type="tel"  name="reserve_phone" value="{{ $employee->reserve_phone }}" class="form-control typeahead">
                    </div>
                </div>

                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>С какого часа</label>
                        <input type="time" name="hour_from" value="{{ $employee->hour_from }}" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        <label>По какой час</label>
                        <input type="time" name="hour_to" value="{{ $employee->hour_to}}" class="form-control typeahead">
                    </div>
                </div>

                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>Фиксированная плата</label>
                        <input type="checkbox" name="fixed_charge" value="fixed" @if($employee->fixed_charge == 'fixed') checked @endif class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Размер платы за смену</label>
                        <input type="number" name="pay_per_shift" value="{{ $employee->pay_per_shift}}" class="form-control typeahead">
                    </div>
                </div>

                <div class="form-row">  

                    <div class="form-group col-md-6">
                        <label>Телеграм ID</label>
                        <input type="text" name="telegram_id" value="{{ $employee->telegram_id }}" class="form-control typeahead">
                    </div>

                    <div class="form-group col-md-6">
                        {{-- Как узнать Телеграм ID : Кнопка открытия модального окна --}}
                        <label>Как узнать Телеграм ID</label><br>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#howToGetId">
                            Туториал
                        </button>
                    </div>

                </div>

                <button type="submit" class="btn btn-success">
                    Применить
                </button>
                
            </form>
            <hr>
            <a href="{{ url('view_employees') }}" class="btn btn-primary">Вернуться</a>

{{-- Как узнать Телеграм ID : Форма и модальное окно --}}
    <form method="POST">
        @csrf

        <div class="modal fade" id="howToGetId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Как узнать Телеграм ID</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <ol> 
                                <li>Зайти в свой телеграм, создать новый канал с именем например @channel_name</li>
                                <li>Добавить в свой канал (@channel_name) бот @fs_sto_bot, как администратора </li>
                                <li>В форму вставить имя созданного канала (@channel_name) </li>
                                
                               <!-- <li>Перейти по ссылке https://telegram.me/userinfobot</li>
                                <li>В открывшемся диалоге с ботом написать /start</li>
                                <li>Получивши сообщение-ответ скопировать ID</li>
                                <li>Вставить в форму</li> -->
                            </ol> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>
@endsection