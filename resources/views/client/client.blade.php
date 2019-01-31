@extends('layouts.limitless')
@section('page_name')
    Список машин клиента
@endsection
@section('content')
    <div class="container"  >
        @if(!$cars->isEmpty())
            @foreach ($cars as $car)
                <div class="card" style="margin-bottom: 20px;margin-top: 10px">
                    <div class="card-body" style="text-align: center">
                        {{($car->general_name)}}
                    </div>
                </div>

                {{-- Наряды клиента : кнопка --}}
                <a href="{{ url('client/assignments/'.$car->id) }}">
                    <div class="btn btn-success">
                        Активные наряды
                    </div>
                </a>

                {{-- Архив : переход --}}
                <a href="{{ url('client/assignments_archive/'.$car->id) }}">
                    <div class="btn btn-light">
                        Архивные наряды
                    </div>
                </a>
            @endforeach
        @else
            <div class="card">
                <div class="card-body" style="text-align: center">
                    Машин даного клієнта поки нема
                </div>
            </div>
        @endif
    </div>
@endsection
