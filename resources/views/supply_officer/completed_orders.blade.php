@extends('layouts.limitless')

@section('page_name')
    Список выполненных заказов
@endsection

@section('content')
    <div class="card card-p">
        {{-- Вывод списка выполненных заказов --}}
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Дата завершения</th>
                <th>Ответственный снабженец</th>
                <!-- <th>Кол-во позиций</th> -->
                <th>Кол-во товара</th>
                <th>Комментарий</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
            </thead>
            <tbody>
            @foreach($supply_orders as $supply_order)
                <tr>
                    <td>
                        {{-- Номер заказа --}}
                        {{ $supply_order->id }}<br>
                    </td>

                    <td>
                        {{-- Имя заказчика --}}
                        {{ $supply_order->creator_name }}<br>
                    </td>

                    <td>
                        {{-- Дата создания --}}
                        {{ $supply_order->date_of_creation }}
                    </td>

                    <td>
                        {{-- Дата завершения --}}
                        {{ $supply_order->date_of_completion }}
                    </td>

                    <td>
                        {{-- Имя ответственного лица --}}
                        {{ $supply_order->responsible_officer_name }}
                    </td>

                <!-- <td>
                        {{-- Количество пунктов --}}
                {{ $supply_order->entries_count }}
                        </td> -->

                    <td>
                        {{-- Количество предметов (штук) --}}
                        {{ $supply_order->items_count }}
                    </td>

                    <td>
                        {{-- Комментарий --}}
                        {{ $supply_order->order_comment }}
                    </td>

                    <td>
                        {{-- Кнопка управления --}}
                        <a href="{{ url('supply_officer/all_orders/view_order/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Просмотр
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- Конец вывода заказов --}}
    </div>

@endsection