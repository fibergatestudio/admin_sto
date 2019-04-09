@extends('layouts.limitless')

@section('page_name')
    Страница машины: {{ $car->general_name }}
    {{-- Вернуться на страницу Машины в сервисе --}}
                <a href="{{ url('/admin/cars_in_service/index') }}" title="На страницу Машины в сервисе">
                    <div class="btn btn-danger">
                        Вернуться
                    </div>
                </a>
@endsection

@section('content')
    {{-- Клиент-владелец : ссылка --}}
    <p>Клиент-владелец: 
        <a href="{{ url('admin/clients/view_client/'.$client->id) }}">
            {{ $client->general_name }}
        </a>
    
    </p>
    <hr>
    {{-- Текущие наряды : вывод --}}

    Текущие наряды по авто:<br>
    @foreach($assignments as $assignment)
        {{-- Название наряда + переход на страницу наряда--}}
        <a href="{{ url('admin/assignments/view/'.$assignment->id) }}">
            {{ $assignment->description }}
        </a>
        <br>

    @endforeach


    <hr>
    {{-- Добавить наряд по машине : переход на страницу --}}
    <a href="{{ url('admin/assignments/add/'.$car->id) }}">
        <div class="btn btn-success">
            Добавить наряд на машину
        </div>
    </a>
    <hr>
    <h3>Примечания к авто</h3>
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
        {{-- Непосредственно вывод данных --}}
            @foreach($car_notes as $car_note)
                <tr>
                    {{-- Текст примечания --}}
                    <td>{{ $car_note->text }}</td>

                    {{-- Автор примечания --}}
                    <td>{{ $car_note->author_name }}</td>

                    {{-- Кнопки управления --}}
                    <td>
                        <a href="{{ url('admin/cars_in_service/edit_note_to_car/'.$car_note->id) }}">                            
                                Редактировать            
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('admin/cars_in_service/delete_note/'.$car_note->id ) }}">
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

    {{-- Кнопка добавить примечание--}}
    <a href="{{ url('/admin/cars_in_service/add_note_to_car/'.$car->id) }}">
        <div class="btn btn-primary">
            Добавить примечание
        </div>
    </a>

@endsection