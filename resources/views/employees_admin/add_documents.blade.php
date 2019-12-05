@extends('layouts.limitless')

@section('page_name')
Добавление документов
<a href="{{ url('/supervisor/view_employees') }}" class="btn btn-danger" title="Страница Сотрудники">Вернуться</a>
@endsection

@section('content')
<div class="card card-p">
    {{-- Форма --}}
    <form action="{{ url('/add_documents_post/') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- ID сотрудника --}}
        <h4>
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <input type="hidden" name="employee_name" value="{{ $employee->general_name }}">
            <div>
                {{ $employee->general_name}}
            </div>
        </h4>
        <div class="d-flex align-items-baseline">
            <div>
                <input type="file" name="scan" class="mx-2">
            </div>
            <div>
                <button type="submit" class="btn btn-success">Загрузить</button>
            </div>
        </div>
    </form>
    <hr>
    <div>
        <a href="{{ url('/supervisor/manage_employee_status/'. $employee->id .'/employee_edit') }}" class="btn btn-danger">Вернуться</a>
    </div>

    {{-- Конец формы --}}
</div>


@endsection
