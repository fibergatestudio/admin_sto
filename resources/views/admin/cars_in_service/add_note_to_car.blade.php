@extends('layouts.limitless')

@section('page_name')
    Добавить примечание к авто
@endsection

@section('content')
    <h2>Добавить примечание к авто</h2>
    <p>
    Машина: {{ $car->general_name }}<br>
    Клиент: {{ $client->general_name }}
    </p>

    {{-- Форма добавления примечания --}}
    <form method="POST" action="{{ url('admin/cars_in_service/add_note_to_car') }}">
        @csrf
        <input type="hidden" name="car_id" value="{{ $car->id }}">
        <div class="form-group">
            <textarea name="note_content" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    {{-- Конец формы --}}



    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/cars_in_service/view/'.$car->id) }}">
        <div class="btn btn-secondary">
            Вернуться
        </div>
    </a>
@endsection