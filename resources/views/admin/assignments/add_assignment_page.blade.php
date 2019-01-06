@extends('layouts.limitless')

@section('page_name')
    Добавить наряд
@endsection

@section('content')
    <p>
        Клиент: {{ $owner->general_name }}<br>
        Авто: {{ $car->general_name }}
    </p>

    {{-- Форма добавления наряда --}}
    <form method="POST" action="{{ url('admin/assignments/add') }}">
        @csrf

        {{-- Идентификатор машины --}}
        <input type="hidden" name="car_id" value="{{ $car->id }}">

        {{-- Название --}}
        <div class="form-group">
            <label>Краткое описание</label>
            <input type="text" name="assignment_description" class="form-control">
        </div>

        {{-- Ответственный работник --}}
        <div class="form-group">
            <label>Ответственный сотрудник</label>
            <select name="responsible_employee_id" class="form-control">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->general_name }}</option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Создать наряд
        </button>



    </form>

    {{-- Конец формы добавления наряда --}}
@endsection