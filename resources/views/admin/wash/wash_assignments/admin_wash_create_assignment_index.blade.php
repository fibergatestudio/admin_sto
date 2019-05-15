@extends('layouts.limitless')

@section('page_name')
    Создание наряда Мойки
    <a href=""><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')

<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    
    <div class="content py-2 col-md-8 offset-md-2">
            <div class="container">
                <div class="form-row">
                    <div class="card card-outline-secondary col-md-12">
                    <form action="{{ url('/admin/wash_assignments/create_assignment') }}" method="POST">
                        @csrf
                        <!-- <input type="hidden" name="assignment_number" value="1"> -->
                        <div class="form-group">
                            <div class="card-header">
                                <h3 class="mb-0">Информация клиента:</h3>
                            </div>
                            <hr>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Имя:</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Телефон:</label>
                                    <input type="number" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="form-row col-md-12">  
                                <div class="form-group col-md-6">
                                    <label>Название фирмы:</label>
                                    <input type="text" name="firm_name" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Тип Клиента:</label>
                                    <select class="form-control" name="client_type">
                                        <option>--Выберите--</option>
                                        <option>Физ. лицо</option>
                                        <option>Юр. лицо</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Название банка:</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Код банка:</label>
                                    <input type="text" name="bank_code" class="form-control">
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Юридический адрес:</label>
                                    <input type="text" name="legal_address" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Банковский счет:</label>
                                    <input type="text" name="bank_account" class="form-control">
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Фиксальный код:</label>
                                    <input type="text" name="fiscal_code" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Код НДС:</label>
                                    <input type="text" name="VAT_code" class="form-control">
                                </div>
                            </div>
                            <!-- <div class="form-row col-md-12">
                                <label>Выбрать из списка:</label>
                                    <select class="form-control" required>
                                        <option>--Выберите--</option>
                                        <option>Тест</option>
                                    </select>
                            </div> -->
                            <hr>
                            <div class="form-row col-md-12">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
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