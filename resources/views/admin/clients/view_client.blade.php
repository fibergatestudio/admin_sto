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
      </div>
    </div>

    
    <h3>Машины клиента:</h3>
    <table class="table">
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
                    <a href="{{ url('admin/cars_in_service/view/'.$car->id) }}">
                        <div class="btn btn-secondary">
                            Подробнее
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    {{-- Добавить машину клиента --}}
    <a href="{{ url('admin/cars_in_service/add/'.$client->id) }}">
        <div class="btn btn-primary">    
            Добавить машину клиента
        </div>
    </a>

    <hr>

    <h3>Примечания к клиенту</h3>
    {{-- Вывод примечаний --}}
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
    {{-- Конец таблицы --}}
    
        {{-- Добавить примечание к клиенту --}}
    <a href="{{ url('admin/clients/add_note_to_client/'.$client->id) }}">
        <div class="btn btn-primary">    
            Добавить примечание к клиенту
        </div>
    </a>

    <hr>
    
   
    

@endsection