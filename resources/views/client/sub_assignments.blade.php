@extends('layouts.limitless')
@section('page_name')
    Зональные наряды клиента
@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Рабочая зона</th>
            <th>Ответственный сотрудник</th>
        </tr>
        </thead>
        @foreach($sub_assignments as $sub_assignment)
            <tr>
                <td>{{($sub_assignment->name)}} {{-- Описание наряда --}}</td>
                <td>{{($sub_assignment->description)}} {{-- Описание наряда --}}</td>
                <td>{{($sub_assignment->general_name)}} {{-- Описание наряда --}}</td>
                <td>{{($sub_assignment->responsible_employee)}} {{-- Описание наряда --}}</td>
            </tr>
        @endforeach
    </table>
@endsection
