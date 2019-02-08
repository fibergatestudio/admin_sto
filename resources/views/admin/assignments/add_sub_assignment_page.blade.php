@extends('layouts.limitless')

@section('page_name')
    Добавление зонального наряла
@endsection

@section('content')
    <form method="POST" action="{{ url('admin/assignments/add_sub_assignment') }}">
        @csrf

        {{-- ID родительского наряда --}}
        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

        {{-- Название --}}
        <div class="form-group">
            <label>Название</label>
            <!-- <input type="text" name="name" class="form-control" required> -->
            <select class="form-control" name="name" required>
                <option value="Разборка-Сборка">Разборка-Сборка</option>
                <option value="Электрика">Электрика</option>
                <option value="Слесарка">Слесарка</option>
                <option value="Рихтовка">Рихтовка</option>
                <option value="Покраска">Покраска</option>
                <option value="Детэйлинг">Детэйлинг</option>
            </select>
        </div>

        {{-- Описание --}}
        <div class="form-group">
            <label>Описание</label>
            <input type="text" name="description" class="form-control">
        </div>

        {{-- Рабочая зона --}}
        <div class="form-group">
            <label>Рабочая зона</label>
            <select name="workzone" class="form-control">
                @foreach($workzones as $workzone)
                    <option value="{{ $workzone->id }}">{{ $workzone->general_name }}</option>
                @endforeach
            </select>
        </div>
        

        {{-- Ответственное лицо --}}
        <div class="form-group">
            <label>Ответственное лицо</label>
            <select name="responsible_employee" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-light">Добавить</button>
    </form>
@endsection