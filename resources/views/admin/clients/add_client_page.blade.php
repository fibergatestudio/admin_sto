@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Форма добавления клиента</h2>

    <form action="{{ url('admin/add_client') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Имя клиента или название организации</label>
            <input type="text" name="general_name">
        </div>

        <button type="submit" class="btn btn-primary">
            Добавить
        </button
        * После добавления клиента можно будет добавить машину клиента

    </form>


@endsection