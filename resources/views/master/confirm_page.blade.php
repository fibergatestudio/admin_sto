@extends('layouts.limitless')
@section('page_name')
    Подтвержденные записи
    <a href="{{ url('/master') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection
@section('content')
    @if($empty==0)
        <table class="table">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Год авто</th>
                <th>Марка авто</th>
                <th>Модель авто</th>
                <th>Номер машины</th>
                <th>Телефон</th>
                <th>Дата записи</th>
                <th>Время записи</th>
                <th></th>
            </tr>
            </thead>
            @foreach($records as $record)
                <tr>
                    <td>{{($record->name)}} {{--  Имя --}}</td>
                    <td>{{($record->car_year)}} {{-- Год авто --}}</td>
                    <td>{{($record->car_brand)}} {{-- Марка авто --}}</td>
                    <td>{{($record->car_model)}} {{-- Модель авто --}}</td>
                    <td>{{($record->car_number)}} {{-- Номер машины --}}</td>
                    <td>{{($record->record_date)}} {{-- Телефон --}}</td>
                    <td>{{($record->phone)}} {{-- Дата записи --}}</td>
                    <td>{{($record->confirmed_time)}} {{-- Время записи --}}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center">
                Подтвержденных записей пока нет
            </div>
        </div>
    @endif
@endsection
