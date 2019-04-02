@extends('layouts.limitless')

@section('page_name')
Добавление документов
@endsection

@section('content')

{{-- Форма --}}
<form action="{{ url('/add_documents_post/') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ID сотрудника --}}
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <input type="hidden" name="employee_name" value="{{ $employee->general_name }}">
    <div>
        {{ $employee->general_name}}
    </div>
    <div class="row">
        
        <div>
            <input type="file" name="scan">
        </div>

        <div>
            <button type="submit" class="btn btn-success">Загрузить</button>
        </div>
    </div>
</form>
<hr>
<a href="{{ url('/supervisor/manage_employee_status/'. $employee->id .'/employee_edit') }}" class="btn btn-danger">Вернуться</a>
{{-- Конец формы --}}

@endsection