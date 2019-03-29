@extends('layouts.limitless')

@section('page_name')
    Редактировать примечание - "<span style="size: 30px; color: #339900;"><b><em>Редактирование примечания</b></em></span>"
@endsection

@section('content')
    <h2>Редактировать примечание клиента</h2>

    {{-- Форма добавления примечания --}}
    <form method="POST" action="{{ url('admin/clients/edit_client_note') }}">
        @csrf
        <input type="hidden" name="note_id" value="{{ $note_id }}">
        <div class="form-group">
            <textarea name="text" class="form-control">{{$client_note->text}}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    {{-- Конец формы --}}

    <hr>
    
    {{-- Вернуться к списку клиентов --}}
    <a href="{{ url('admin/clients/clients_index') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>

    </a>
    

@endsection