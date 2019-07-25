@extends('layouts.limitless')

@section('page_name')
    Список заказов
@endsection

@section('content')
    {{-- Вывод списка всех заказов --}}
    @foreach($supply_orders as $supply_order)
    <h5><span class="badge badge-primary">Заказ {{ $supply_order->id }}</span></h5>
    <table class="table">
        <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <!-- <th>Кол-во позиций</th> -->
                <th>Кол-во товара</th>
                <th>Комментарий</th>
                <th>Стоимость</th>
                <th>Способ Оплаты</th>
                <th>Кому Выдал</th>
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
                                Детали и управление
                            </div>
                        </a>
                    </td>

                <form class="form" action="{{ url('/supply_officer/all_orders/list/apply_order_edit') }}" method="POST">
                    @csrf

                    {{-- Счётчик количества вхождений --}}
                    <input type="hidden" id="counter" name="order_id" value="{{ $supply_order->id }}">

                    <td>
                        {{ $supply_order->order_price }}
                        <input type="text" class="form-control" name="order_price" value="{{ $supply_order->order_price }}" required> </input>
                    </td>

                    <td>
                        {{ $supply_order->payment_method }}
                        <select class="form-control" name="payment_method">
                        
                            <option>Выберите</option>
                            <option>Наличные</option>
                            <option>Счет-фактура</option>
                            <option>По перечислению</option>
                        </select>
                    </td>

                    <td>
                        {{ $supply_order->given_to }}

                        <select class="form-control" name="given_to">
                        
                        <option>Выберите</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->general_name }}</option>
                        @endforeach

                        </select>
                    </td>

                    <td> 
                    <button type="submit" class="btn btn-success">Применить</button>
                    </form>
                    @if(!empty($supply_order->order_price))
                    <form method="POST" action="{{ url('supply_officer/order_completed_action') }}">
                        @csrf
                        {{-- ID выполненного заказа --}}
                        <input type="hidden" name="order_id" value="{{ $supply_order->id }}">

                        <input type="hidden" name="order_price" value="{{ $supply_order->order_price }}">
                        <input type="hidden" name="payment_method" value="{{ $supply_order->payment_method }}">

                        <button type="submit" class="btn btn-success">
                            Исполнено
                        </button>
                    </form>
                    @endif
                    </td>
                </tr>            
        </tbody>
    </table>
    <div>
        <hr>
        <span>Подробности заказа</span>
    </div>
    <table class="table bg-white" >
        <thead>
            <tr>
                <th>Название товара</th>
                <th>Количество</th>
                <th>Срочность</th>                
            </tr>
        </thead>
        <tbody class=""> 
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
    <hr>
    @endforeach
    {{-- Конец вывода заказов --}}
@endsection