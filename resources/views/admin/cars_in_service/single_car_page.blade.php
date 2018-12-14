@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Страница машины: {{ $car->general_name }}</h2>
    <p>Клиент: {{ $client->general_name }}</p>
    
    {{-- Добавить наряд по машине : переход на страницу --}}
    <a href="{{ url('admin/assignments/add/'.$car->id) }}">
        <div class="btn btn-success">
            Добавить наряд 
        </div>
    </a>


    <hr>
    {{-- Возврат в карточку клиента : переход на страницу--}}
    <a href="#">
        <div class="btn btn-secondary">
            В карточку клиента
        </div>
    </a>
@endsection