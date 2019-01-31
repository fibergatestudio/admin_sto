@extends('layouts.limitless')

@section('page_name')
    Добавление нового рабочего поста
@endsection


@section('content')
    <h2>Изменение рабочего поста</h2>
    <form action="{{ url('admin/workzones/edit') }}" method="POST">
        @csrf
        <input type="hidden" name="workzones_id" value="{{ $workzones_id }}">
        <div class="form-group">
            <label>Название</label>
            <input type="text" class="form-control" name="general_name" value="{{$names}}" required>
        </div>

        <div class="form-group">
            <label>Описание</label>
            <input type="text" class="form-control" name="description" value="{{$descriptions}}" required></input>
        </div>

        <button type="submit" class="btn btn-primary">
            Изменить
        </button>

    </form>
    <hr>

    {{-- Вернуться на страницу всех постов --}}
    <a href="{{ url('admin/workzones/index') }}">
        <div class="btn btn-secondary">
            Вернуться
        </div>
    </a>
@endsection