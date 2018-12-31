@extends('layouts.limitless')

@section('page_name')
    Заказы для поставщиков
@endsection

@section('content')
    {{-- Выводим заказы --}}
    <table class="table">
        <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Кол-во позиций</th>
                <th>Кол-во товара</th>
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
                        {{-- Кнопка управления --}}
                        <a href="{{ url('/admin/supply_orders/manage/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Управление
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>

    {{-- Новый заказ : кнопка --}}
    <a href="{{ url('admin/supply_orders/new') }}">
        <div class="btn btn-success">
            Новый заказ
        </div>
    </a>

    {{-- Архив : переход --}}
    <a href="{{ url('admin/supply_orders/archive') }}">
        <div class="btn btn-light">
            Архив
        </div>
    
    </a>
@endsection