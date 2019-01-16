@extends('layouts.limitless')

@section('page_name')
<div class="row">
    <div class="col-sm-3">
        <p>Модели</p>
    </div>
    <div class="col-sm-3">
        {{-- Добавить модель машны : Кнопка открытия модального окна --}}
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCarModelModal">
            Добавить модель машнины
        </button>
    </div>

</div>
@endsection


@section('content')

    <div class="row text-center">
        <div class="col-lg-12">
            <ul class="pagination">
                {{ $car_models->links() }}                    
            </ul>
        </div>
    </div>

    <table class="table">
    <thead>
        <tr>
            <th>Полное название</th>
            <th>Брэнд</th>
            <th>Модель</th>
            <th></th>{{-- Кнопки управления --}}
        </tr>
    </thead>
        @foreach($car_models as $model)
            <tr>
                <td>
                {{ $model->general_name }}<br>
                </td>
                <td>
                {{ $model->brand }}<br>
                </td>
                <td>
                {{ $model->model }}<br>
                </td>
                <td>
                    <a href="{{ url('/admin/cars/'.$model->id.'/car_edit') }}">
                        <button type="button" class="btn btn-info">
                            Редактировать
                        </button>
                    </a>
                </td>
                <td>
                    <a href="{{ url('/admin/cars/'.$model->id.'/delete') }}">
                    
                        <button type="button" class="btn btn-warning">
                            Удалить
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="row text-center">
        <div class="col-lg-12">
            <ul class="pagination">
                {{ $car_models->links() }}                    
            </ul>
        </div>
    </div>

    {{-- Добавить модель машины : Форма и модальное окно --}}
    <form action="{{ url('/admin/cars/add_car_entry') }}" method="POST">
        @csrf

        <div class="modal fade" id="addCarModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить модель машины</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
  
                            {{-- Полное название --}}
                            <div class="form-group">
                                <label>Полное название</label>
                                <input type="text" name="general_name" class="form-control" required > 
                            </div>

                            {{-- Брэнд --}}
                            <div class="form-group">
                                <label>Брэнд</label>
                                <input type="text" name="brand" class="form-control" required>
                            </div>
                            {{-- Модель --}}
                            <div class="form-group">
                                <label>Модель</label>
                                <input type="text" name="model" class="form-control" required>
                            </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

    <hr>



@endsection