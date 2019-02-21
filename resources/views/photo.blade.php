@extends('layouts.limitless')

@section('page_name')
    Телеграм: Отправка фото
@endsection

@section('content')
    <form action="{{ url('/store-photo') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group">
            <div class="custom-file">
                <input type="file" id="file" name="file" class="custom-file-input">
                <label class="custom-file-label" for="file">Выберите файл</label>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
@endsection