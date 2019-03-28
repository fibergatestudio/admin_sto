@extends('layouts.limitless')

@section('page_name')
    Редактировать примечание к авто
@endsection

@section('content')
    <h2>Редактировать примечание к авто</h2>

    {{-- Форма редактирования примечания --}}
    <form method="POST" action="{{ url('admin/cars_in_service/edit_note_to_car') }}">
        @csrf
        <input type="hidden" name="note_id" value="{{ $note_id }}">
        <div class="form-group">
            <textarea name="text" class="form-control">{{ $car_note->text }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    {{-- Конец формы --}}



    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/cars_in_service/view/' .$car_note->car_id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection