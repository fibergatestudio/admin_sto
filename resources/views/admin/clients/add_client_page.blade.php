@extends('layouts.limitless')

@section('page_name')
    Форма добавления клиента
    {{-- Вернуться к списку клиентов --}}
     <a href="{{ url('admin/clients/clients_index') }}" title="К списку клиентов">
        <div class="btn btn-danger">
            Вернуться
        </div>

    </a>
@endsection

@section('content')
    <form action="{{ url('admin/clients/add_client') }}" method="POST">
        @csrf
        <!-- <div class="form-group">
            <label>Имя клиента или название организации (Old)</label>
            <input class="form-control" type="text" name="general_name">
        </div> -->
        <div class="form-group">
            <label>Имя</label>
            <input class="form-control" type="name" name="name" required>
        </div>
        <div class="form-group">
            <label>Фамилия</label>
            <input class="form-control" type="text" name="surname" required>
        </div>
        <div class="form-group">
            <label>Организация</label>
            <input class="form-control" type="text" name="organization">
        </div>
        <div class="form-group">
            <label>Телефон</label>
            <input class="form-control" type="number" name="phone" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <p>* После добавления клиента можно будет добавить машину клиента</p>

    </form>


@endsection