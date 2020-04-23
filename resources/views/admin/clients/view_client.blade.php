@extends('layouts.limitless')

@section('page_name')
    Карточка клиента: {{ $client->fio }}
     {{-- Вернуться к списку клиентов --}}
     <a href="{{ url('admin/clients/clients_index') }}" title="К списку клиентов">
        <div class="btn btn-danger">
            Вернуться
        </div>

    </a>
@endsection

@section('content')
    <h3>Информация о клиенте:</h3>

    {{-- Статическая информация по наряду --}}
    <div class="row">
      <div class="list-group col-md-6">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Клиент:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $client->fio }}</h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Дата регистрации:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $client->created_at }}</h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Скидка:</h5>
            </div>
            <h4 class="mb-1 text-success">
                @if($client->discount == '')
                    0 %
                @else
                    {{ $client->discount }} %
                @endif
            </h4>
        </a>
      </div>
      <div class="list-group col-md-6">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Номер телефона:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $client->phone }}</h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Примечание:</h5>
          </div>
          <h4 class="mb-1 text-success">
            @if(!empty($client_notes))
                @foreach($client_notes as $notes)
                    {{ $notes->text }}
                @endforeach
            @else
                Нету примечания
            @endif
            </h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Редактирование:</h5>
          </div>
          <button class="btn btn-success" type="button" class="px-5 btn btn-primary" data-toggle="modal" data-target="#editInfo">Изменить Данные</button>
        </a>
      </div>
    </div>


    <div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Редактировать</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('admin/clients/view_client/'. $client->id .'/edit_info') }}" method="POST">
                    @csrf
                        <div class="modal-body">

                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                
                                <div class="form-group">
                                    <label>ФИО</label>
                                    <input type="text" class="form-control" value="{{ $client->fio }}" name="fio">
                                </div>


                                <div class="form-group">
                                    <label>Номер телефона</label>
                                    <input type="number" class="form-control" value="{{ $client->phone }}" name="phone">
                                </div>


                                <div class="form-group">
                                    <label>Скидка</label>
                                    <input type="number" class="form-control" value="{{ $client->discount }}" name="discount">
                                </div>


                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary" >Применить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



<h3 class="mt-2">Машины клиента:</h3>
<div class="card card-p">
    <table class="table table-car-client">
        <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Модель/марка</th>
            <th>Год</th>
            <th>Рег номер</th>
            <th>VIN</th>
            <th>Цвет</th>
            <th></th> {{-- Кнопки управления --}}
        </tr>
        </thead>

        <tbody>
        @foreach($cars as $car)
            <tr>
                {{-- Номер Наряда --}}
                <td>{{ $car->id }}</td>

                {{-- Дата --}}
                <td>{{ $car->created_at }}</td>

                {{-- Название машины --}}
                <td>{{ $car->general_name }}</td>

                {{-- Год --}}
                <td>{{ $car->release_year }}</td>

                {{-- Рег номер --}}
                <td>{{ $car->reg_number }}</td>

                {{-- VIN --}}
                <td>{{ $car->vin_number }}</td>

                {{-- Цвет --}}
                <td>@if (!empty($car->car_color ))
                        <i style="width:35px; height:35px; display:flex;background-color:{{ $car->car_color }}; border: 2px solid rgb(97, 97, 97);"></i>
                    @else
                        null
                    @endif
                </td>
                {{-- Кнопка подробнее --}}
                <td>
                    <a href="{{ url('admin/cars_in_service/view_assignments/'.$car->id) }}">
                        <div class="btn btn-primary">
                            Наряды
                        </div>
                    </a>
                    <a href="{{ url('admin/cars_in_service/view/'.$car->id) }}">
                        <div class="btn btn-secondary">
                            Подробнее
                        </div>
                    </a>
                    <a href="#">
                        <div class="btn btn-success" data-toggle="modal" data-target="#editCarModal{{ $car->id }}">
                            Ред
                        </div>
                    </a>
                </td>
            </tr>

            {{-- Редактировать машину : Форма и модальное окно --}}
            <form action="{{ url('admin/clients/view_client/' . $client->id . '/' . $car->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Редактировать машину #{{ $car->id }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="{{ $car->id }}">

                                <div class="form-group">
                                    <label>Полное название</label>
                                    <input type="text" name="general_name" class="form-control" value="{{ $car->general_name }}" required >
                                </div>

                                <div class="form-group">
                                    <label>Год выпуска</label>
                                    <input type="number" min="1900" max="2099" step="1" name="release_year" class="form-control" value="{{ $car->release_year }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Регистрационный номер</label>
                                    <input type="text" name="reg_number" class="form-control" value="{{ $car->reg_number }}" required>
                                </div>

                                <div class="form-group">
                                    <label>VIN Номер</label>
                                    <input type="text" name="vin_number" class="form-control" value="{{ $car->vin_number }}" required>
                                </div>

                            <!-- <div class="form-group col-md-12 row">

                                <div class="form-group col-md-6">
                                    <label>Пробег в милях</label>
                                    <input type="number" name="mileage_miles" class="form-control" min="0" id="mileageMiles" step="any" value="{{ $car->mileage_miles }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Пробег в километрах</label>
                                    <input type="number" name="mileage_km" class="form-control" min="0" id="mileageKM" step="any" value="{{ $car->mileage_km }}" required>
                                </div>

                            </div> -->

                                <div class="form-group">
                                    <label>Тип топлива</label>
                                    <select name="fuel_type" id="fueltype" class="form-control" required>
                                        @if ($fuel_type->isNotEmpty())
                                            @foreach ($fuel_type as $fuel)
                                                <option value="{{ $fuel->fuel_name }}">{{ $fuel->fuel_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-success">Применить</button>
                            </div>
                        </div>{{-- /modal-content --}}
                    </div>{{-- /modal-dialog --}}
                </div>{{-- /modal fade --}}
            </form>

        @endforeach
        </tbody>
    </table>
</div>



    {{-- Добавить машину клиента --}}
    <a href="{{ url('admin/cars_in_service/add/'.$client->id) }}">
        <div class="btn btn-primary">
            Добавить машину клиента
        </div>
    </a>

    <hr>

    <h3>Примечания к клиенту</h3>
    {{-- Вывод примечаний --}}
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>
                <th>Текст</th>
                <th>Автор</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            {{--  Вывод данных --}}
            @foreach($client_notes as $client_note)
                <tr>
                    {{-- Текст примечания --}}
                    <td>{{ $client_note->text }}</td>

                    {{-- Автор примечания --}}
                    <td>{{ $client_note->author_name }}</td>

                    {{-- Кнопки управления --}}
                    <td>
                        <a href="{{ url('admin/clients/edit_client_note/'.$client_note->id ) }}">Редактирование
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('admin/client/delete_client_note/'.$client_note->id ) }}">
                            <div class="btn btn-danger">
                                X
                            </div>
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    {{-- Конец таблицы --}}

        {{-- Добавить примечание к клиенту --}}
    <a href="{{ url('admin/clients/add_note_to_client/'.$client->id) }}">
        <div class="btn btn-primary">
            Добавить примечание к клиенту
        </div>
    </a>

    <hr>




@endsection
