<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница нарядов
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Дата создания</th>
            <th scope="col">Ответственный сотрудник</th>
            <th scope="col">Авто</th>
            <th scope="col">Зона</th>
            </tr>
        </thead>
        @foreach($assignments as $assignment)
            <tr>
                {{-- Дата --}}
                <td>{{ beautify_date($assignment->date_of_creation) }}</td>
                
                {{-- Ответственный сотрудник --}}
                <td>{{ $assignment->employee_name }}</td>
                
                {{-- Машина --}}
                <td>{{ $assignment->car_name }}</td>

                {{-- Пост --}}
                <td>{{ $assignment->workzone_name }}</td>

                {{-- Кнопка подробнее --}}
                <td>
                    <a href="#">
                        <div class="btn btn-secondary">
                            Подробнее
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    
    * Добавление наряда будет осуществляться из карточки машины (вкладка "Клиенты" или "Машины в сервисе")
@endsection