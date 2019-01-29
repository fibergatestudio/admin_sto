@extends('layouts.limitless')

@section('page_name')
    Добавить примечание к сотруднику - "<span style="size: 30px; color: #339900;"><b><em> {{ $employee->general_name }} </b></em></span>"
@endsection

@section('content')
    <h2>Добавить примечание к сотруднику</h2>


    {{-- Форма добавления примечания --}}
    <form method="POST" action="{{ url('admin/employee/add_note_to_employee') }}">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <div class="form-group">
            <textarea name="note_content" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    {{-- Конец формы --}}



    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('/view_employees') }}">
        <div class="btn btn-secondary">
            Вернуться
        </div>
    </a>
@endsection