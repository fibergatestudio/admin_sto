@extends('layouts.limitless')

@section('page_name')
    Страница удаления фотографий
@endsection

@section('content')        
    <p>Удаление Принятых машин:</p>
    <div class="row">
    @foreach($accepted_image_urls as $image)
        {{-- Изображение --}}
        <img style="height: 300px; width: auto;" src="{{ Storage::url($image) }}">

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
    </div>
    <p>Удаление процесс ремонта</p>
    <div class="row">
    @foreach($repair_image_urls as $image)
        {{-- Изображение --}}
        <img style="height: 300px; width: auto;" src="{{ Storage::url($image) }}" width="200px">

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
    </div>

    <p>Удаление выдача готовых</p>
    <div class="row">
    @foreach($finished_image_urls as $image)
        {{-- Изображение --}}
        <img style="height: 300px; width: auto;" src="{{ Storage::url($image) }}" width="200px">

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
    </div>

    {{-- Кнопка вернуться : на страницу наряда --}}
    <a href="{{ url('admin/assignments/view/'.$assignment_id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection