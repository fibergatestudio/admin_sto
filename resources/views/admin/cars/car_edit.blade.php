@extends('layouts.limitless')

@section('page_name')
Редактирование Модели
 {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/cars/index') }}" class="btn btn-danger" title="Вернуться к машинам">Вернуться</a> 
@endsection


@section('content')

{{-- Добавить модель машны : Кнопка открытия модального окна --}}
    <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Полное название</th>
            <th>Брэнд</th>
            <th>Модель</th>
            <th></th>{{-- Кнопки управления --}}
        </tr>
    </thead>
            <tr>
                <td>
                {{ $car_model->id }}<br>
                </td>
                <td>
                {{ $car_model->general_name }}<br>
                </td>
                <td>
                {{ $car_model->brand }}<br>
                </td>
                <td>
                {{ $car_model->model }}<br>
                </td>
                <td>
                {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editCarModelModal">
                    Изменить
                </button><br>
                </td>
               
            </tr>
    </table>

    {{-- Редактировать модель машины : Форма и модальное окно --}}
    <form action="{{ url('/admin/cars/'.$car_model->id.'/submit_car_entry') }}" method="POST">
        @csrf

        <div class="modal fade" id="editCarModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Редактировать модель машины</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                            <input type="hidden" name="id" value="{{ $car_model->id }}">

                            {{-- Полное название --}}
                            <div class="form-group">
                                <label>Полное название</label>
                                <input type="text" name="new_general_name" class="form-control" value="{{ $car_model->general_name }}" required > 
                            </div>

                            {{-- Брэнд --}}
                            <div class="form-group">
                                <label>Брэнд</label>
                                <input type="text" name="new_brand" class="form-control" value="{{ $car_model->brand }}" required>
                            </div>
                            {{-- Модель --}}
                            <div class="form-group">
                                <label>Модель</label>
                                <input type="text" name="new_model" class="form-control" value="{{ $car_model->model }}" required>
                            </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-success">Изменить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

    <hr>

@endsection