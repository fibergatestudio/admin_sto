@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Новый сотрудник</h2>

    <form action="{{ url('add_employee') }}" method="POST">
        @csrf
        {{-- Фамилия --}}
        <div class="form-group">
            <label>Фамилия</label>
            <input type="text" class="form-control" name="surname">
        </div>

        {{-- Имя --}}
        <div class="form-group">
            <label>Имя</label>
            <input type="text" class="form-control" name="name">
        </div>

        <button class="btn btn-primary">Добавить</button>
    </form>
@endsection