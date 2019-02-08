@extends('layouts.limitless')
@section('page_name')
    Профиль
@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Имя</th>
            <th></th>
            <th>ФИО</th>
            <th></th>
            <th>Организация</th>
            <th></th>
            <th>Телефон</th>
            <th></th>
            <th>Баланс</th>
            <th></th>
            <th>Личная скидка</th>
        </tr>
        </thead>

                <tr>
                    <td>{{($profile->general_name)}} {{--  Статус --}}</td>
                    <td></td>
                    <td>{{($profile->fio)}} {{--  Статус --}}</td>
                    <td></td>
                    <td>{{($profile->organization)}} {{-- Ответственный работник --}}</td>
                    <td></td>
                    <td>{{($profile->phone)}} {{-- Ответственный работник --}}</td>
                    <td></td>
                    <td>{{($profile->balance)}} {{-- Ответственный работник --}}</td>
                    <td></td>
                    <td>{{($profile->discount)}} {{-- Ответственный работник --}}</td>

                </tr>


    </table>
@endsection
