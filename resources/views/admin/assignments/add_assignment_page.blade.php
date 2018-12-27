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
    <form>
        @csrf
        
        {{-- ... --}}

        {{-- Описание --}}
        
        {{-- Ответственный работник --}}

        
        <button type="submit" class="btn btn-primary">
            Создать наряд

        </button>



    </form>

    {{-- Конец формы добавления наряда --}}
@endsection