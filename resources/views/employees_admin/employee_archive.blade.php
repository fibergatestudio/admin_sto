@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Архив сотрудников</h2>
    @foreach($archived_employees as $archived_employee)
        {{ $archived_employee->general_name }}
    @endforeach
    <hr>
    <a href="{{ url('view_employees') }}">
        <div class="btn btn-primary">
            Вернуться
        </div>
    </a>
@endsection