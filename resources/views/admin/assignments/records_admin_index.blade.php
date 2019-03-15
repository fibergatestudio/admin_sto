@extends('layouts.limitless')

@section('page_name')
    Страница Записей
@endsection

@section('content')        

{{-- Таблица-вывод данных записей --}}
<table class="table">
        <thead>
            <tr>
                <th>Статус</th>
                <th>Имя</th>
                <th>Год Авто</th>
                <th>Марка Авто</th>
                <th>Модель Авто</th>
                <th>Номер машины</th>
                <th>Дата записи</th>
                <th>Телефон</th>
                <th>Время записи</th>
                <th></th>{{-- Кнопки управления --}}
                <th></th>{{-- Кнопки удаления --}}
            </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
        <tr>
            <td>
                @if($record->status == 'confirmed')
                <b class="badge bg-success" type="text">{{ $record->status }}</b><br>
                @else
                <b class="badge bg-warning" type="text">{{ $record->status }}</b><br>
                @endif
            </td>
            <td>
            {{ $record->name }}<br>
            </td>
            <td>
            {{ $record->car_year }}<br>
            </td>
            <td>
            {{ $record->car_brand }}<br>
            </td>
            <td>
            {{ $record->car_model }}<br>
            </td>
            <td>
            {{ $record->car_number }}<br>
            </td>
            <td>
            {{ $record->record_date }}<br>
            </td>
            <td>
            {{ $record->phone }}<br>
            </td>
            <td>
                @if($record->status == 'unconfirmed')
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ url('/complete_record/'.$record->id) }}" method="POST">
                        @csrf
                            <select style="width:auto;" class="dropdown form-control" style='width:60px;' name="confirmed_time" onchange='return timeSchedvalue(this.value)'>
                                @foreach($record->available_time as $time_option)
                                    <option value="{{ $time_option }}">{{ $time_option }}</option>
                                @endforeach
                            <select></select>
                            <input type="hidden" name="record_id" value="{{ $record->id }}">
                            <input type="submit" value="Подтвердить" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ url('/delete_record/'.$record->id) }}" method="POST">
                        @csrf
                            <input type="hidden" name="record_id" value="{{ $record->id }}">
                            <input type="submit" value="Удалить" class="btn btn-warning"/>
                        </form>
                        @else
                            {{ $record->confirmed_time }}
                        @endif
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</select> 

{{-- Форма добавления записи --}}
<form action="{{ url('/add_record') }}" method="POST">
    @csrf
<div class="content py-5  bg-light">
    <div class="container">
        <div class="row">
                <div class="col-md-8 offset-md-2">
                <span class="anchor" id="formUserEdit"></span>
                <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3 class="mb-0">Форма Записи (тест)</h3>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" autocomplete="off">

                        <input type="hidden" name="status" value="unconfirmed">

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Имя</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="name" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Год Авто</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_year" type="number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Марка Авто</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_brand" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Модель Авто</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_model" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Номер машины</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="car_number" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Дата записи</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="record_date" type="date"  value="2019-02-12" min="2019-01-01" max="2019-12-31">
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Телефон</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="phone" type="number">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection