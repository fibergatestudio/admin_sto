@extends('layouts.limitless')

@section('page_name')
    Страница Записей
@endsection

@section('content')        
<div class="card card-p">
    {{-- Таблица-вывод данных записей --}}
    <table class="table">
        <thead>
        <tr>
            <th>Статус</th>
            <th>Имя</th>
            <th>Год Авто</th>
            <th>Марка Авто</th>
            <th>Модель Авто</th>
            <th>Номер машины</th>
            <th>Дата записи</th>
            <th>Телефон</th>
            <th>Время записи</th>
            <th></th>{{-- Кнопки управления --}}
        </tr>
        </thead>
        <tbody>
        @foreach($confirmed_records as $record)
            <tr>
                <td>
                    @if($record->status == 'confirmed')
                        <b class="badge bg-success" type="text">{{ $record->status }}</b><br>
                    @else
                        <b class="badge bg-warning" type="text">{{ $record->status }}</b><br>
                    @endif
                </td>
                <td>
                    {{ $record->name }}<br>
                </td>
                <td>
                    {{ $record->car_year }}<br>
                </td>
                <td>
                    {{ $record->car_brand }}<br>
                </td>
                <td>
                    {{ $record->car_model }}<br>
                </td>
                <td>
                    {{ $record->car_number }}<br>
                </td>
                <td>
                    {{ $record->record_date }}<br>
                </td>
                <td>
                    {{ $record->phone }}<br>
                </td>
                <td>
                    {{ $record->confirmed_time }}<br>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</select> 


@endsection