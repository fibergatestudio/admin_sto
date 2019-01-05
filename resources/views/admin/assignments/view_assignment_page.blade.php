@extends('layouts.limitless')

@section('page_name')
Наряд: {{ $assignment->description }}
@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    Клиент: {{-- ... --}} <br>
    Авто: {{-- ... --}}<br>

    {{-- Зональные наряды --}}
    {{-- ... вывод ... --}}
    
    {{-- Добавить новый зональный наряд --}}
    {{-- ... --}}
    <a href="{{ url('admin/assignments/add_sub_assignment/'.$assignment->id) }}">
        <div class="btn btn-light">
            Новый зональный наряд
        </div>
    </a>
    

    {{-- История --}}
    {{-- ... --}}

    {{-- Финансы --}}
    {{-- ... --}}

    {{-- Фотографии --}}
    {{-- ... --}}

@endsection