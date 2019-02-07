@extends('layouts.limitless')
@section('page_name')
    Список машин клиента
@endsection
@section('content')
    <table class="table">
        <thead>
        <tr>

            <th>Модель</th>
            <th></th>
            <th>Брэнд</th>
            <th></th>
            <th>Возраст машины</th>
            <th></th>
            <th></th>
        </tr>
        </thead>

        @if(!$cars->isEmpty())
            @foreach ($cars as $car)
                <tr>

                    <td>{{($car->car_model->model)}} {{--  Статус --}}</td>
                    <td></td>
                    <td>{{($car->car_model->brand)}} {{--  Статус --}}</td>
                    <td></td>
                    <td>{{($car->release_year)}} {{-- Ответственный работник --}}</td>

                    <td>
                        {{-- Наряды клиента : кнопка --}}
                        <a href="{{ url('client/assignments/'.$car->id) }}">
                            <div class="btn btn-success">
                                Активные наряды
                            </div>
                        </a>
                    </td>
                    <td>
                        {{-- Архив : переход --}}
                        <a href="{{ url('client/assignments_archive/'.$car->id) }}">
                            <div class="btn btn-light">
                                Архивные наряды
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <div class="card">
                <div class="card-body" style="text-align: center">
                    Машин даного клієнта поки нема
                </div>
            </div>
        @endif

    </table>
@endsection
