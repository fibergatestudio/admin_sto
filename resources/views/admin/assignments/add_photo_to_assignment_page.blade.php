@extends('layouts.limitless')

@section('page_name')
Добавление фото к наряду
@endsection

@section('content')

{{-- Форма --}}

{{-- Убрать --}}
<p>Загрузка фото в общую папку (убрать)</p>
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

{{-- Форма --}}
<p>Принятая машина</p>
<form action="{{ url('admin/assignments/add_accepted_photo_to_assignment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ID наряда, к которому сохраняется фотография --}}
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    
    <div class="row">
        
        <div>
            <input type="file" name="accepted_photo">
        </div>

        <div>
            <button type="submit" class="btn btn-success">Загрузить</button>
        </div>
    </div>
    
</form>
{{-- Конец формы --}}

{{-- Форма --}}
<p>Процесс ремонта</p>
<form action="{{ url('admin/assignments/add_repair_photo_to_assignment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ID наряда, к которому сохраняется фотография --}}
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    
    <div class="row">
        
        <div>
            <input type="file" name="repair_photo">
        </div>

        <div>
            <button type="submit" class="btn btn-success">Загрузить</button>
        </div>
    </div>
    
</form>
{{-- Конец формы --}}

{{-- Форма --}}
<p>Готовая машина</p>
<form action="{{ url('admin/assignments/add_finished_photo_to_assignment') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ID наряда, к которому сохраняется фотография --}}
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    
    <div class="row">
        
        <div>
            <input type="file" name="finished_photo">
        </div>

        <div>
            <button type="submit" class="btn btn-success">Загрузить</button>
        </div>
    </div>
    
</form>
{{-- Конец формы --}}

@endsection