@extends('layouts.limitless')
@section('page_name')
    Список машин клієнта
@endsection
@section('content')
    <div class="container">
        @if(!$cars->isEmpty())
            @foreach ($cars as $car)
                <div class="card" style="margin-bottom: 20px">
                    <div class="card-body" style="text-align: center">
                        {{($car['general_name'])}}
                    </div>
                </div>
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
