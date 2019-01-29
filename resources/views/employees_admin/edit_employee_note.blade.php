@extends('layouts.limitless')

@section('page_name')
    Редактировать примечание - "<span style="size: 30px; color: #339900;"><b><em>Редактирование примечания</b></em></span>"
@endsection

@section('content')
    <h2>Редактировать примечание сотрудника</h2>

    {{-- Форма добавления примечания --}}
    <form method="POST" action="{{ url('admin/employee/edit_employee_note') }}">
        @csrf
        <input type="hidden" name="note_id" value="{{ $note_id }}">
        <div class="form-group">
            <textarea name="text" class="form-control">{{ $employee_note->text }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    {{-- Конец формы --}}


    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/employee/'.$employee_id) }}">
        <div class="btn btn-secondary">
            Вернуться
        </div>
    </a>
@endsection