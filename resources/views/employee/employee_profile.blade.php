@extends('layouts.limitless')

@section('page_name')
Мой профиль
@endsection

@section('content')
{{-- Отображения данных текущего профиля --}}
<div class="content bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <span class="anchor" id="formUserEdit"></span>
                <div class="card card-outline-secondary">
                    <div class="card-header">
                        <h3 class="mb-0">Мой Профиль</h3>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" autocomplete="off">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Имя пользователя</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value=" {{ $employee_edit->general_name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">ФИО</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value=" {{ $employee_edit->fio }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Номер паспорта</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="email" value="{{ $employee_edit->passport }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Баланс</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="{{ $employee_edit->balance }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Номер телефона</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="url" value="{{ $employee_edit->phone }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Резервный номер</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="url" value=" {{ $employee_edit->reserve_phone }}" readonly>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Время работы</label>
                                <label class="col-lg-1 col-form-label form-control-label">С:</label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" value="{{ $employee_edit->hour_from }}" readonly>
                                </div>
                                <label class="col-lg-1 col-form-label form-control-label">По:</label>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" value="{{ $employee_edit->hour_to }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Телеграм ID</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value=" {{ $employee_edit->telegram_id }}" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection