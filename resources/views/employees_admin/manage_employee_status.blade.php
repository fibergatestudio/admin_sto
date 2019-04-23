@extends('layouts.limitless')

@section('page_name')
Управление статусом сотрудника: <b>{{ $employee->general_name }}</b>
{{-- Вернуться в прошлое меню --}}
    <a href="{{ url('/supervisor/view_employees') }}" title="Страница Сотрудники">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection

@section('content')
    <?php
        $status_array = [
            'active' => 'Действующий',
            'archived' => 'Архивная запись'
        ];
    ?>

    <p>Текущий статус: {{ $status_array[$employee->status] }}</p>
    
    {{-- Перевести сотрудника в архив --}}
    <form action="{{ url('/supervisor/archive_employee') }}" method="POST">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

        <button type="submit" class="btn btn-primary">
            Перевести в архив
        </button>

    </form>

    <hr>
    

@endsection