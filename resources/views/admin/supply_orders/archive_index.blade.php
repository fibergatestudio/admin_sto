@extends('layouts.limitless')

@section('page_name')
    Архив заказов на поставку
     {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/supply_orders/index') }}" class="btn btn-danger">Вернуться к заказам</a> 
@endsection

@section('content')
{{-- Выводим заказы --}}
    @foreach($archived_orders as $supply_order)
    <h5><span class="badge badge-secondary">Заказ {{ $supply_order->id }}</span></h5>
    <div class="card">
        <table class="table">
            <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Кол-во позиций</th>
                <th>Кол-во товара</th>
                <th>Комментарий к заказу</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
            </thead>

            <tbody>

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
                    {{-- Комментарий --}}
                    {{ $supply_order->order_comment }}
                </td>

                <td>
                    {{-- Кнопки управления --}}
                    {{-- Просмотреть
                    <a href="#">
                        <div class="btn btn-primary">
                            Просмотр
                        </div>
                    </a>--}}

                    {{-- Удалить --}}
                    <a href="{{ url('/admin/supply_orders/archive/delete/'.$supply_order->id) }}">
                        <div class="btn btn-danger">
                            Удалить
                        </div>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>
        <span>Подробности заказа</span>
    </div>
    <div class="card">
        <table class="table" >
            <thead>
            <tr>
                <th>Название товара</th>
                <th>Количество</th>
                <th>Срочность</th>
            </tr>
            </thead>
            <tbody>
            @foreach($supply_order->items as $supply_order->item)
                <tr>
                    <td >
                        {{-- Название --}}
                        {{ $supply_order->item->item }}<br>
                    </td>

                    <td >
                        {{-- Количество --}}
                        {{ $supply_order->item->number }}
                    </td>

                    <td>
                        {{-- Срочност --}}
                        @if($supply_order->item->urgency == 'Не горит')
                            <span class="badge badge-success">{{$supply_order->item->urgency}}</span>
                        @elseif($supply_order->item->urgency == 'Горит')
                            <span class="badge badge-warning">{{$supply_order->item->urgency}}</span>
                        @elseif($supply_order->item->urgency == 'Очень горит')
                            <span class="badge badge-danger">{{$supply_order->item->urgency}}</span>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

     <hr>
    @endforeach
      
   <hr>
   
@endsection