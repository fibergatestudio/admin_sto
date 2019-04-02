@extends('layouts.limitless')

@section('page_name')
    Страница удаления документов сотрудника
@endsection

@section('content')
    @foreach($images as $image)
        {{-- Изображение --}}
        <img src="{{ Storage::url($image) }}" width="200px">

        {{-- Кнопка удаления --}}
        <form method="POST" action="{{ url('/documents_delete_post/') }}">
            @csrf

            {{-- ID работника --}}
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">

            {{-- Путь к файлу --}}
            <input type="hidden" name="path_to_file" value="{{ $image }}">
        
            <button type="submit" class="btn btn-danger">
                Х
            </button>
        </form>

        <hr>
    @endforeach

    {{-- Кнопка вернуться : на страницу с документами --}}
    <a class="btn btn-danger" href="{{ url('/supervisor/manage_employee_status/'. $employee_id .'/documents') }}">Вернуться</a>
@endsection
