@extends('layouts.limitless')
@section('page_name')
    Список машин клиента
@endsection
@section('content')
    <table class="table">
        @if(!$cars->isEmpty())
        <thead>
        <tr>
            <th>Марка</th>
            <th></th>
            <th>Модель</th>
            <th></th>
            <th>Год выпуска</th>
            <th></th>
            <th></th>
        </tr>
        </thead>

            @foreach ($cars as $car)
                <tr>
                    <td>{{($car->car_model->brand)}} {{--  Марка --}}</td>
                    <td></td>
                    <td>{{($car->car_model->model)}} {{--  Модель --}}</td>
                    <td></td>
                    <td>{{($car->release_year)}} {{-- Год выпуска --}}</td>

                    <td>
                        {{-- Активные наряды клиента : кнопка --}}
                        <a href="{{ url('client/cars/assignments/'.$car->id) }}">
                            <div class="btn btn-success">
                                Активные наряды
                            </div>
                        </a>
                    </td>
                    <td>
                        {{--  Архивные наряды : переход --}}
                        <a href="{{ url('client/cars/assignments_archive/'.$car->id) }}">
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
                    Машин данного клиента пока нет
                </div>
            </div>
        @endif

    </table>
@endsection
