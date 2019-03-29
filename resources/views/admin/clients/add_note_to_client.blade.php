@extends('layouts.limitless')

@section('page_name')
    Добавить примечание к клиенту - "<span style="size: 30px; color: #339900;"><b><em> {{ $client->general_name }} </b></em></span>"
@endsection

@section('content')
    <h2>Добавить примечание к клиенту</h2>
<!--     <p>
    Клиент: {{ $client->general_name }}
    </p> -->

    {{-- Форма добавления примечания --}}
    <form method="POST" action="{{ url('admin/clients/add_note_to_client') }}">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <div class="form-group">
            <textarea name="note_content" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    {{-- Конец формы --}}



    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/clients/view_client/'.$client->id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection