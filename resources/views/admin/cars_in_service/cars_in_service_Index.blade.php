@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <h2>Все машины в сервисе</h2>
    <div class="card card-p">
        <table class="table">
            <tr>
                <td><b>Машина</b></td>
                <td><b>Клиент</b></td>
            </tr>
            @foreach($cars as $car)
                <tr>
                    {{-- Машина --}}
                    <td>
                        <a href="{{ url('/admin/cars_in_service/view/'.$car->id) }}">
                            {{ $car->general_name }}
                        </a>

                    </td>

                    {{-- Клиент : имя + ссылка --}}
                    <td>
                        <a href="{{ url('admin/clients/view_client/'.$car->owner_client_id) }}">
                            {{ $car->client_name }}
                        </a>
                    </td>

                </tr>
            @endforeach
        </table>
    </div>


    {{-- Добавить машину --}}
    <a href="{{ url('admin/cars_in_service/add') }}">
        <div class="btn btn-success">
            Добавить машину
        </div>
    </a>
@endsection