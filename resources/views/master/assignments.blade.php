<?php
function beautify_date($mysql_date){
    $date_dump = explode('-', $mysql_date);
    return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
}
?>
@extends('layouts.limitless')
@section('page_name')
    
@endsection
@section('content')
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Дата создания</th>
                <th scope="col">Название</th>
                <th scope="col">Ответственный сотрудник</th>
                <th scope="col">Авто</th>
                <th scope="col">Статус</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            @foreach($assignments as $assignment)
                <tr>
                    {{-- Дата --}}
                    <td>{{ beautify_date($assignment->date_of_creation) }}</td>

                    {{-- Название наряда --}}
                    <td>{{ $assignment->description }}</td>

                    {{-- Ответственный сотрудник --}}
                    <td>{{ $assignment->employee_name }}</td>

                    {{-- Машина --}}
                    <td>{{ $assignment->car_name }}</td>

                    {{-- Статус --}}
                    <td>
                        @if ($assignment->status == 'active')
                            Невыполнен
                        @else
                            Выполнен<br>Требует подтверждения
                        @endif
                    </td>

                    {{-- Кнопка подробнее --}}

                    <td>
                        @if ($assignment->status == 'active')

                        @else
                            <a href="#">
                                <div class="btn btn-success">
                                    Подтвердить
                                </div>
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/master/assignments/view/'.$assignment->id) }}">
                            <div class="btn btn-secondary">
                                Подробнее
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <hr>
@endsection
