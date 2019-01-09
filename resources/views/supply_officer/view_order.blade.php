@extends('layouts.limitless')

@section('page_name')
Просмотр заказа № {{ $supply_order->id }}
@endsection

@section('content')
    {{-- Вывод основной информации по заказу --}}
    
        {{-- Имя заказчика --}}
        Имя заказчика: {{ $supply_order->creator_name }}<br>
        {{-- Дата создания в виде ДД.ММ.ГГГГ --}}
        Дата создания: {{ $supply_order->date_of_creation }}
        

    <hr>
    {{-- Вывод деталей по заказу --}}
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supply_order_items as $item)
                <tr>
                    <td>
                        {{-- Название --}}
                        {{ $item->item }}
                    </td>

                    <td>
                        {{-- Описание --}}
                        {{ $item->description }}
                    </td>

                    <td>
                        {{-- Количество --}}
                        {{ $item->number }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Конец вывода деталей по заказу --}}

    <hr>
    {{-- Заказ выполнен : Кнопка --}}
    <form method="POST" action="{{ url('supply_officer/order_completed_action') }}">
        @csrf
        {{-- ID выполненного заказа --}}
        <input type="hidden" name="order_id" value="{{ $supply_order->id }}">

        <button type="submit" class="btn btn-success">
            Заказ выполнен
        </button>
    </form>

    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('supply_officer/all_orders') }}">
        <div class="btn btn-dark">
            Вернуться
        </div>
    </a>
    
@endsection