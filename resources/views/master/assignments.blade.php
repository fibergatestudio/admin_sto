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
            <th scope="col">Название</th>
            <th scope="col">Ответственный сотрудник</th>
            <th scope="col">Авто</th>
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

                @if($assignment->confirmed == 'confirmed')
                    <td style="text-align: center">
                        <b class="badge bg-success" type="text">{{ $assignment->confirmed }}</b><br>
                    </td>
                    @else
                    <td>
                        <a href="{{ url('/master/confirm/'.$assignment->id) }}">
                            <div class="btn btn-warning">
                                Подтвердить запись
                            </div>
                        </a>
                    </td>
                    @endif

                {{-- Кнопка подробнее --}}

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
    <hr>
@endsection