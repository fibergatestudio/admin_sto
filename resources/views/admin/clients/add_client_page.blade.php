@extends('layouts.limitless')

@section('page_name')
    Форма добавления клиента
@endsection

@section('content')
    <form action="{{ url('admin/add_client') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Имя клиента или название организации</label>
            <input type="text" name="general_name">
        </div>

        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <p>* После добавления клиента можно будет добавить машину клиента</p>

    </form>


@endsection