@extends('layouts.limitless')

@section('page_name')
    Страница удаления фотографий
@endsection

@section('content')
    @foreach($images as $image)
        {{-- Изображение --}}
        <img src="{{ Storage::url($image) }}" width="200px">

        {{-- Кнопка удаления --}}
        <form method="POST" action="{{ url('/admin/assignments/delete_photo_from_assignment') }}">
            @csrf

            {{-- ID наряда --}}
            <input type="hidden" name="assignment_id" value="{{ $assignment_id }}">

            {{-- Путь к файлу --}}
            <input type="hidden" name="path_to_file" value="{{ $image }}">
        
            <button type="submit" class="btn btn-danger">
                Х
            </button>
        </form>

        <hr>
    @endforeach

    {{-- Кнопка вернуться : на страницу наряда --}}
    <a href="{{ url('admin/assignments/view/'.$assignment_id) }}">
        <div class="btn btn-light">
            Вернуться
        </div>
    </a>
@endsection