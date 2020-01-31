@extends('layouts.limitless')
@section('page_name')

@endsection
@section('content')
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>

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
                <td>{{($profile->fio)}} {{--  ФИО --}}</td>
                <td></td>
                <td>{{($profile->organization)}} {{-- ОРГАНИЗАЦИЯ --}}</td>
                <td></td>
                <td>{{($profile->phone)}} {{-- ТЕЛЕФОН --}}</td>
                <td></td>
                <td>{{($profile->balance)}} MDL {{-- БАЛАНС --}}</td>
                <td></td>
                <td>{{($profile->discount)}} {{-- СКИДКА --}}</td>

            </tr>


        </table>
    </div>

@endsection
