@extends('layouts.limitless')

@section('page_name')
    Учёт мойки
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Учёт мойки:</h3>
            </div>
            <hr>
            <div class="form-row col-md-12">  
                <div class="form-group col-md-3">
                    <label>Марка</label>
                    <input type="text" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                    <label>Номер</label>
                    <input type="number" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                    <label>Номер прицепа</label>
                    <input type="number" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Фирма</label>
                    <input type="text" class="form-control" required>
                </div>
            </div>
            <div class="form-row col-md-12">  
                <div class="form-group col-md-3">
                    <label>Метод оплаты</label>
                    <select class="form-control" required>
                        <option>--Выберите метод оплаты--</option>
                        <option>Наличный</option>
                        <option>Безналичный</option>
                    </select>
                </div>

                <div class="form-group col-md-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" class="custom-control-input" id="check1" v-model="fine">
                        <label class="custom-control-label" for="check1">Наружка</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="2" class="custom-control-input" id="check2" v-model="fine">
                        <label class="custom-control-label" for="check2">Внутрянка</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="3" class="custom-control-input" id="check3" v-model="fine">
                        <label class="custom-control-label" for="check3">Наружка без сушки</label>
                    </div>
                </div>
                <div class="form-group col-md-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="4" class="custom-control-input" id="check4" v-model="fine">
                        <label class="custom-control-label" for="check4">Мотор</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="5" class="custom-control-input" id="check5" v-model="fine">
                        <label class="custom-control-label" for="check5">Радиатор</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="6" class="custom-control-input" id="check6" v-model="fine">
                        <label class="custom-control-label" for="check6">Нет талона</label>
                    </div>
                </div>
                <div class="form-group col-md-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="7" class="custom-control-input" id="check7" v-model="fine">
                        <label class="custom-control-label" for="check7">Терминал</label>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label>Сумма</label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Бокс</label>
                    <select class="form-control" required>
                        <option>--Выберите номер бокса--</option>
                        <option>1</option>
                        <option>2</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-row col-md-12">
            <button class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </div>
    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Учёт мойки, Таблица:</h3>
            </div>
            
            <!-- <div class="row">
                <div class="col">
                    <div class="custom-control">
                        <label>Фильтры типа: </label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" class="custom-control-input" id="check1" v-model="income">
                        <label class="custom-control-label" for="check1">Начисления</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="2" class="custom-control-input" id="check2" v-model="payout">
                        <label class="custom-control-label" for="check2">Выплаты</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="3" class="custom-control-input" id="check3" v-model="fine">
                        <label class="custom-control-label" for="check3">Штрафы</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="4" class="custom-control-input" id="check4" v-model="coffee">
                        <label class="custom-control-label" for="check4">Кофе</label>
                    </div>
                </div>
                <div class="col">
                    <div class="btn btn-info">Сорт.Старые</div>
                </div>
            </div> -->
        </form>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Марка</th>
                        <th>Номер</th>
                        <th>Номер прицепа</th>
                        <th>Фирма</th>
                        <th>Метод оплаты</th>
                        <th>Чекбоксы</th>
                        <th>Сумма</th>
                        <th>Бокс</th>
                    </tr>
                </thead>
            <tbody >
                    <tr >
                        <td>
                           
                        </td>
                        <td>
                           
                        </td>
                        <td>
                         
                        </td>
                        <td>
                           
                        </td>
                        <td>
                           
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>       
            </tbody>
            </table>
        </div>
    </div>
</div>

@endsection