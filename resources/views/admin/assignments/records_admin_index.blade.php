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
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
        <tr>
            <td>
            {{ $record->status }}<br>
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
            {{-- Кнопка подтверждения записи --}}
            <a href="{{ url('/complete_record/'.$record->id) }}">
                <div class="btn btn-primary">
                    Подтвердить
                </div>
            </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

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
                                    <input class="form-control" name="record_date" type="date">
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