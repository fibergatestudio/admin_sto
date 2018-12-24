@extends('layouts.limitless')

@section('page_name')
Управление статусом сотрудника: <b>{{ $employee->general_name }}</b>
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
    <form action="{{ url('archive_employee') }}" method="POST">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

        <button type="submit" class="btn btn-primary">
            Перевести в архив
        </button>

    </form>

    <hr>
    {{-- Вернуться в прошлое меню --}}
    <a href="{{ url('view_employees') }}">
        <div class="btn btn-seconday">
            Вернуться
        </div>
    </a>

@endsection