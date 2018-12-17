@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Добавление нового рабочего поста</h2>
    <form action="{{ url('admin/workzones/add') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Название*</label>
            <input type="text" class="form-control" name="general_name" required>
        </div>

        <div class="form-group">
            <label>Описание</label>
            <textarea class="form-control" name="description"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Добавить
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