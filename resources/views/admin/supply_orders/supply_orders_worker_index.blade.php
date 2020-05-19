@extends('layouts.limitless')

@section('page_name')
    Заказы для поставщиков (необходимо подтвердить)
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/supply_orders/index') }}" class="btn btn-danger">Вернуться к заказам</a>
@endsection

@section('content')
    {{-- Выводим заказы --}}
    
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>
                <th>№ заказа</th>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Название товара</th>
                <th>Количество</th>
                <th>Срочность</th>
                <th>Комментарий</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
            </thead>
            <tbody>
           
            @foreach($supply_orders as $supply_order)
            <tr>

                <td >
                    {{-- № заказа --}}
                    <span class="badge badge-primary">Заказ {{ $supply_order->id }}</span><br>
                </td>
                
                <td >
                    {{-- Имя заказчика --}}
                    {{ $supply_order->creator_name }}<br>
                </td>

                <td >
                    {{-- Дата создания --}}
                    {{ $supply_order->date_of_creation }}
                </td>

                @foreach($supply_order->items as $supply_order->item)
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
                @endforeach

                <td >
                    {{-- Комментарий --}}
                    {{ $supply_order->order_comment }}

                </td>

                <td >
                    {{-- Кнопки управления --}}
                    <a href="{{ url('/admin/supply_orders/edit/'.$supply_order->id) }}">
                        <div class="btn btn-primary">
                            Редактировать
                        </div>
                    </a>
                    <hr>
                    <a href="{{ url('/admin/supply_orders/confirm/'.$supply_order->id) }}">
                        <div class="btn btn-primary">
                            Подтвердить
                        </div>
                    </a>

                </td>
            </tr>


   @endforeach  
    
    </tbody>
</table>    
</div>    

@endsection