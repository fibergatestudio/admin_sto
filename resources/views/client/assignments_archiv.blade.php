@extends('layouts.limitless')
@section('page_name')
    Архив нарядов клиента
    <a href="{{ url('/client') }}">
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
                <th>Дата создания</th>
                <th>Название</th>
                <th>Ответственный работник</th>
                <th>Авто</th>
                <th></th>
            </tr>
            </thead>
            @foreach($assignments_archiv as $assignment)
                <tr>
                    <td>{{($assignment->date_of_creation)}} {{-- Дата создания --}}</td>
                    <td>{{($assignment->description)}} {{-- Описание наряда --}}</td>
                    <td>{{($assignment->employee_name)}} {{-- Ответственный работник --}}</td>
                    <td>{{($assignment->car_name)}} {{-- Назва авто --}}</td>
                    <td>
                        <a href="{{ url('/client/sub_assignments/'.$assignment->id) }}">
                            <div class="btn btn-secondary">
                                Архив зональных нарядов
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center">
                Архивных нарядов нет
            </div>
        </div>
    @endif
@endsection
