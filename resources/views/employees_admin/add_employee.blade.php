@extends('layouts.limitless')

@section('page_name')
    Добавить сотрудника
@endsection

@section('content')
    <h2>Новый сотрудник</h2>

    <form action="{{ url('add_employee') }}" method="POST">
        @csrf
        {{-- Фамилия --}}
        <div class="form-group">
            <label>Фамилия</label>
            <input type="text" class="form-control" name="surname" required>
        </div>

        {{-- Имя --}}
        <div class="form-group">
            <label>Имя</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        {{-- Логин --}}
        <div class="form-group">
            <label>Логин</label>
            <input type="text" name="login" class="form-control" required>
        </div>

        {{-- Пароль --}}
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Добавить</button>
    </form>
@endsection