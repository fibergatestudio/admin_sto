@extends('layouts.limitless')

@section('page_name')
    Изменение записи
@endsection

@section('content')        

{{-- Форма изменения записи --}}
<form action="{{ url('/records/'.$record->id.'/apply_edit') }}" method="POST">
    @csrf
<div class="content py-10  bg-light">
    <div class="container">
        <div class="row">
                <div class="col-md-12 offset-md-0">
                <span class="anchor" id="formUserEdit"></span>
                <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3 class="mb-0">Редактирование записи</h3>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" autocomplete="off">

                        <input type="hidden" name="status" value="unconfirmed">
                        <input type="hidden" name="id" value="{{ $record->id }}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label form-control-label">Имя</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="name" value="{{ $record->name }}" type="text" disabled>
                                </div>
                                <label class="col-lg-2 col-form-label form-control-label">Телефон</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="phone" value="{{ $record->phone }}" type="number" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label form-control-label">Марка Авто</label>
                                <div class="col-lg-4">
                                    <input id="carBrand"  class="form-control typeahead" name="car_brand" value="{{ $record->car_brand }}" type="text" disabled>
                                </div>
                                <label class="col-lg-2 col-form-label form-control-label">Модель Авто</label>
                                <div class="col-lg-4">
                                    <input id="carModel" class="form-control" name="car_model" value="{{ $record->car_model }}" type="text" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label form-control-label">Номер машины</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="car_number" value="{{ $record->car_number }}" type="text" disabled>
                                </div>
                                <label class="col-lg-2 col-form-label form-control-label">Год Авто</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="car_year" value="{{ $record->car_year }}" type="number" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label form-control-label">Дата записи</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="record_date" type="date"  value="{{ $record->record_date }}" min="2019-01-01" max="2019-12-31" disabled>
                                </div>
                                <label class="col-lg-2 col-form-label form-control-label">Время</label>
                                <div class="col-lg-4" style="display: flex;">
                                    <input class="form-control" name="record_time" type="time" value="{{ $record->record_time }}" disabled>
                                </div>
                            </div>

                            <hr>
                            <h3 class="mb-0">Назначение услуг</h3><br>
                            @foreach ($explode_services as $es )

                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label form-control-label">Услуга</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="service_name" value="{{ $es }}" type="text" disabled>
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Мастер</label>
                                <div class="col-lg-3" style="display: flex;">
                                    <select class="form-control" name="master_name" type="time">
                                    </select>
                                </div>
                                <label class="col-lg-2 col-form-label form-control-label">Кол-во постов</label>
                                <div class="col-lg-2" style="display: flex;">
                                    <select class="form-control" name="posts_count" type="time">
                                    </select>
                                </div>
                            </div>

                            @endforeach

                            <hr>
                            <h3 class="mb-0">Расчет времени</h3><br>
                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label form-control-label">Дата</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="work_date" value="" type="date">
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Время начала</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="time_start" value="" type="time">
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">Время окончания</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="time_finish" value="" type="time">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Изменить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection