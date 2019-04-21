@extends('layouts.limitless')

@section('page_name')
    Добавление нового рабочего поста
@endsection


@section('content')
    <h2>Добавление нового рабочего поста</h2>
    <form action="{{ url('admin/workzones/add/post') }}" method="POST">
        @csrf
        <div class="form-group">
            <div class="col-md-12">
                <label>Название*</label>
                <input type="text" class="form-control" name="general_name" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <label>Цвет рабочей зоны</label>
                <div id="cp2" class="input-group colorpicker-component"> 
                    <span class="input-group-addon"><i style="width:35px; height:35px; display:flex;"></i></span> 
                    <input type="text" name="workzone_color" value="#00AABB" class="form-control" /> 
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <label>Направление работ</label>
                <select class="form-control" name="works_direction" required>
                    @foreach($work_directions as $direction)
                    <option value="{{ $direction->name }}">{{ $direction->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <label>Описание</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Добавить
        </button>

    </form>
    <hr>

    {{-- Вернуться на страницу всех постов --}}
    <a href="{{ url('admin/workzones/index') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $('#cp2').colorpicker();
    </script>

@endsection