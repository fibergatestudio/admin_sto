@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Учёт мойки, Таблица:</h3>
            </div>
        </form>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Клиент</th>
                        <th>Кол-во моек</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
            <tbody >
                    <tr>
                        <th>Всего</th>
                        <th>------</th>
                        <th></th>
                        <th></th>
                    </tr> 
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection