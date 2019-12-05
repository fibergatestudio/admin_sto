@extends('layouts.limitless')

@section('page_name')
    <div class="row">
        <div class="col-lg-10">
            Редактирование сотрудника: {{ $employee->general_name }}
        </div>
    <?php
        $status_array = [
            'active' => 'Действующий',
            'archived' => 'Архивная запись'
        ];
    ?>
        <div class="col-lg-2">
            {{-- Перевести сотрудника в архив --}}
            <form action="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/archive_employee') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <button type="submit" class="btn btn-primary">
                    Перевести в архив
                </button>

            </form>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            Текущий статус: {{ $status_array[$employee->status] }}
        </div>
    </div>


@endsection

@section('content')
    <div class="card card-p">
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
    </div>


    {{-- Форма изменения данных работника --}}
        <h2>Форма изменения данных</h2>
<div class="card card-p">
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

            <div class="form-group col-md-4">
                <label>ФИО</label>
                <input type="text" name="fio" value="{{ $employee->fio }}" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>День Рождения</label>
                <input type="date" name="birthday" value="{{ $employee->birthday }}" class="form-control">
            </div>


            <div class="form-group col-md-4">
                <label>Баланс</label>
                <input type="number" name="balance" value="{{ $employee->balance }}" class="form-control">
            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">
                <label>Серия и номер паспорта</label>
                <input type="text" name="passport" value="{{ $employee->passport }}" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>Телефон</label>
                <input type="text" name="phone" value="{{ $employee->phone }}" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>Резервный телефон</label>
                <input type="tel"  name="reserve_phone" value="{{ $employee->reserve_phone }}" class="form-control">
            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">
                <label>С какого часа</label>
                <input type="time" name="hour_from" value="{{ $employee->hour_from }}" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>По какой час</label>
                <input type="time" name="hour_to" value="{{ $employee->hour_to}}" class="form-control">
            </div>

            <div class="form-group col-md-2">
                <label>Размер платы за смену</label>
                <input type="number" name="pay_per_shift" value="{{ $employee->pay_per_shift }}" class="form-control">
            </div>

            <div class="form-group col-md-2">
                <label>Фиксированная плата</label>
                <input type="checkbox" name="fixed_charge" value="fixed" @if($employee->fixed_charge == 'fixed') checked @endif class="form-control">
            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-4">
                <label>Должность</label>
                <input type="text" name="position" value="{{ $employee->position }}" class="form-control typeahead">
            </div>

            <div class="form-group col-md-4">
                <label>Место дислокации (р.зона)</label>
                <select name="workzone" id="categories" class="form-control">
                    @foreach ($workzones as $wz )
                        <option value="{{ $wz->id}}">{{ $wz->general_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <hr>

        <div class="form-row">

            <div class="form-group col-md-4">
                <label>Телеграм ID</label>
                <input type="text" name="telegram_id" value="{{ $employee->telegram_id }}" class="form-control typeahead">
            </div>

            <div class="form-group col-md-3">
                {{-- Как узнать Телеграм ID : Кнопка открытия модального окна --}}
                <label>Как узнать Телеграм ID</label><br>
                <button type="button" class="px-5 btn btn-primary" data-toggle="modal" data-target="#howToGetId">
                    Туториал
                </button>
            </div>
            <div class="form-group col-md-2">
                <label>Добавить документы</label>
                <a class="btn btn-secondary" href="{{ url('/supervisor/manage_employee_status/'. $employee->id .'/add_documents') }}">Добавить документы</a>
            </div>
            <div class="form-group col-md-2">
                <label>Просмотреть документы</label>
                <a class="btn btn-secondary" href="{{ url('/supervisor/manage_employee_status/'. $employee->id .'/documents') }}">Посмотреть документы</a>
            </div>

        </div>

        <button type="submit" class="btn btn-success">
            Применить
        </button>

    </form>
    <hr>
</div>



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
                                <li>Перейти по ссылке https://telegram.me/userinfobot</li>
                                <li>В открывшемся диалоге с ботом написать /start</li>
                                <li>Получивши сообщение-ответ скопировать ID</li>
                                <li>Вставить в форму</li>
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
