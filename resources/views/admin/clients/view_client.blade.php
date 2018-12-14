@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Карточка клиента: {{ $client->general_name }}</h2>
    <h3>Машины клиента:</h3>
    <p>
        @foreach($cars as $car)
            {{ $car->general_name }}
        @endforeach
    </p>
    
    {{-- Добавить машину клиента --}}
    <a href="{{ url('admin/cars_in_service/add/'.$client->id) }}">
        <div class="btn btn-primary">    
            Добавить машину клиента
        </div>
    </a>

    <hr>
    
    {{-- Вернуться к списку клиентов --}}
    <a href="{{ url('admin/clients_index') }}">
        <div class="btn btn-secondary">
            Вернуться
        </div>

    </a>
    

@endsection