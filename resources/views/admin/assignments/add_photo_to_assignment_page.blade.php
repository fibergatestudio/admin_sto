@extends('layouts.limitless')

@section('page_name')
Добавление фото к наряду
@endsection

@section('content')

{{-- Форма --}}
<form action="{{ url('admin/assignments/add_photo_to_assignment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ID наряда, к которому сохраняется фотография --}}
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    
    <div class="row">
        
        <div>
            <input type="file" name="test">
        </div>

        <div>
            <button type="submit" class="btn btn-success">Загрузить</button>
        </div>
    </div>
</form>
{{-- Конец формы --}}

@endsection