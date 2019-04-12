@extends('layouts.limitless')

@section('page_name')
    Настройки печати
@endsection

@section('content')

{{-- Форма настройки печати --}}
<div class="content py-2 col-md-8 offset-md-2">
    <div class="container">
        <div class="form-row">
            <div class="card card-outline-secondary col-md-12">
                <div class="form-group">
                    <div class="card-header">
                        <h3 class="mb-0">Настройки печати:</h3>
                    </div>
                    <hr>
                    <div class="form-row col-md-12">  
                        <div class="form-group col-md-6">
                            <label>Официальный номер наряда</label>
                            <input type="text" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Тип документа</label>
                            <select class="form-control" required>
                                <option>--Выберите тип документа--</option>
                                <option>Счет на оплату</option>
                                <option>Заказ-Наряд</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">  
                        <div class="form-group col-md-6">
                            <label>Дата документа:</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Документ для</label>
                            <select class="form-control" required>
                                <option>--Выберите--</option>
                                <option>Физ. лицо</option>
                                <option>Юр. лицо</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-6">
                            <label>НДС</label>
                            <select class="form-control" required>
                                <option>--Выберите тип НДС--</option>
                                <option>Добавить НДС</option>
                                <option>Без НДС</option>
                                <option>Цена с НДС</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Показать цены</label>
                            <select class="form-control" required>
                                <option>--Выберите--</option>
                                <option>Да</option>
                                <option>Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Заметка</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Лого для накладной</label>
                            <select class="form-control" required>
                                <option>--Выберите--</option>
                                <option>Да</option>
                                <option>Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Накладная</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Статус накладной</label>
                            <select class="form-control" required>
                                <option>--Выберите--</option>
                                <option>Выпущено</option>
                                <option>Завершён</option>
                                <option>Отменён</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-row col-md-12">
                        <button class="btn btn-primary">Сохранить</button>
                        <a href="{{ URL::previous() }}">
                            <div class="btn btn-danger">
                                Вернуться
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection