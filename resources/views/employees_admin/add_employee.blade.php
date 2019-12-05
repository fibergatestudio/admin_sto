@extends('layouts.limitless')

@section('page_name')
    Добавить сотрудника
@endsection

@section('content')
    <div class="card card-p">
        <h2>Новый сотрудник</h2>

        <form action="{{ url('/supervisor/add_employee') }}" method="POST">
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

            {{-- Отчество --}}
            <div class="form-group">
                <label>Отчество</label>
                <input type="text" class="form-control" name="fathers_name">
            </div>

            <input type="hidden" name="date_join" value="{{ date("d.m.Y") }}">

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

            {{-- Создавать счет? --}}
            <div class="form-group">
                <label>Создавать счет?</label>
                <input type="checkbox" name="invoice" class="check-custom form-control" required>
            </div>

            <button class="btn btn-primary">Добавить</button>
        </form>
    </div>
    <hr>
    <div class="card-px">
        <a href="{{ url('/supervisor/view_employees') }}" class="btn btn-danger">Вернуться</a>
    </div>

@endsection