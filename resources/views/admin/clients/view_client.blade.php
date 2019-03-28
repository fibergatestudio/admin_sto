@extends('layouts.limitless')

@section('page_name')
    Карточка клиента: {{ $client->general_name }}
@endsection

@section('content')
    
    <h3>Машины клиента:</h3>
    <p>
        @foreach($cars as $car)
            <a href="{{ url('admin/cars_in_service/view/'.$car->id) }}">
                {{ $car->general_name }}
            </a>
        @endforeach
    </p>
    
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
    
    {{-- Вернуться к списку клиентов --}}
    <a href="{{ url('admin/clients/clients_index') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>

    </a>
    

@endsection