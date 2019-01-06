@extends('layouts.limitless')

@section('page_name')
    Архив заказов на поставку
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
            @foreach($archived_orders as $supply_order)
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
                        {{-- Кнопки управления --}}
                        {{-- Просмотреть --}}
                        <a href="#">
                            <div class="btn btn-primary">
                                Просмотр
                            </div>
                        </a>
                        
                        {{-- Удалить --}}
                        <a href="{{ url('/admin/supply_orders/archive/delete/'.$supply_order->id) }}">
                            <div class="btn btn-danger">
                                Удалить
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection