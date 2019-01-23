@extends('layouts.limitless')

@section('page_name')
    Телеграм: Отправление сообщения
@endsection

@section('content')
    <form action="{{ url('/store-message') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="email">Email адрес</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Введите email адрес">
        </div>
        <div class="form-group">
            <label for="message">Сообщение</label>
            <textarea name="message" id="message" class="form-control" placeholder="Введите ваше сообщение" rows="10"></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
@endsection