@extends('layouts.limitless')

@section('page_name')
    Архив сотрудников
@endsection

@section('content')
    @foreach($archived_employees as $archived_employee)
        {{ $archived_employee->general_name }}
    @endforeach
    <hr>
    <a href="{{ url('/supervisor/view_employees') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection