@extends('layouts.limitless')

@section('page_name')
    Добавление нового рабочего поста
@endsection


@section('content')
    <h2>Изменение рабочего поста</h2>
    
    <form action="{{ url('admin/workzones/edit') }}" method="POST">
        @csrf
        <input type="hidden" name="workzones_id" value="{{ $workzones_id }}">
        <div class="form-group">
            <div class="col-md-12">
                <label>Название</label>
                <input type="text" class="form-control" name="general_name" value="{{ $workzone->general_name }}"  required>
            </div>
        </div>

        @if ( !empty( $workzone->workzone_color ))
        <div class="form-group">
            <div class="col-md-12">
                <label>Цвет рабочей зоны</label>
                <div id="cp2" class="input-group colorpicker-component"> 
                    <span class="input-group-addon"><i style="width:35px; height:35px; display:flex;"></i></span> 
                    <input type="text" name="workzone_color" value="{{ $workzone->workzone_color }}" class="form-control" /> 
                </div>
            </div>
        </div>
        @else
        <div class="form-group">
            <div class="col-md-12">
                <label>Цвет рабочей зоны</label>
                <div id="cp2" class="input-group colorpicker-component"> 
                    <span class="input-group-addon"><i style="width:35px; height:35px; display:flex;"></i></span> 
                    <input type="text" name="workzone_color" value="Цвет не задан" class="form-control" placeholder="Цвет не задан" /> 
                </div>
            </div>
        </div>
        @endif

        <div class="form-group">
            <div class="col-md-12">
                <label>Направление работ</label>
                <!-- <input type="text" name="name" class="form-control" required> -->
                <select class="form-control" name="works_direction" required>
                    <option value="Разборка-Сборка">Разборка-Сборка</option>
                    <option value="Электрика">Электрика</option>
                    <option value="Слесарка">Слесарка</option>
                    <option value="Рихтовка">Рихтовка</option>
                    <option value="Покраска">Покраска</option>
                    <option value="Детэйлинг">Детэйлинг</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <label>Описание</label>
                <input type="text" class="form-control" name="description" value="{{ $workzone->description }}" required></input>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Изменить
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