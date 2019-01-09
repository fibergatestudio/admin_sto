@extends('layouts.limitless')

@section('page_name')
    Список заказов
@endsection

@section('content')
    {{-- Вывод списка всех заказов --}}
    <table class="table">
        <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Кол-во позиций</th>
                <th>Кол-во товара</th>
                <th>Срочность</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
            @foreach($supply_orders as $supply_order)
                <tr>
                    <td>
                        {{-- Имя заказчика --}}
                        {{ $supply_order->creator_name }}<br>
                    </td>
                    
                    <td>
                        {{-- Дата создания --}}
                        {{ $supply_order->date_of_creation }}
                    </td>

                    <td>
                        {{-- Количество пунктов --}}
                        {{ $supply_order->entries_count }}
                    </td>

                    <td>
                        {{-- Количество предметов (штук) --}}
                        {{ $supply_order->items_count }}
                    </td>

                    <td>
                        {{-- Срочность --}}
                        {{ $supply_order->urgency }}
                    </td>

                    <td>
                        {{-- Кнопка управления --}}
                        <a href="{{ url('supply_officer/view_order/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Детали и управление
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Конец вывода заказов --}}
@endsection