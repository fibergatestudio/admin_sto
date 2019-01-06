@extends('layouts.limitless')

@section('page_name')
    Страница машины: {{ $car->general_name }}
@endsection

@section('content')
    <p>Клиент: {{ $client->general_name }}</p>
    
    {{-- Добавить наряд по машине : переход на страницу --}}
    <a href="{{ url('admin/assignments/add/'.$car->id) }}">
        <div class="btn btn-success">
            Добавить наряд 
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



    <hr>
    {{-- Возврат в карточку клиента : переход на страницу--}}
    <a href="#">
        <div class="btn btn-secondary">
            В карточку клиента
        </div>
    </a>
@endsection