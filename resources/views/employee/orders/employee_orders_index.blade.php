@extends('layouts.limitless')

@section('page_name')
    Заказы для поставщиков
@endsection

@section('content')

        <div class="row">
            {{-- Новый заказ : кнопка --}}
            <div class="col-2">
                <a href="{{ url('employee/order/new') }}" class="btn btn-success">Новый заказ</a>
            </div>           
        
            {{-- Заказы со статусом активный (подтвержденные) : кнопка --}}
            <div class="col-2">
                <a href="{{ url('employee/orders/active') }}" class="btn btn-info">Утвержденные заказы</a>
            </div> 
            {{-- Завершенные заказы : переход --}}
            <div class="col-2">
                <a href="{{ url('employee/orders/completed') }}" class="btn btn-info">Завершенные заказы</a>
            </div> 
        </div>
        <hr>
    {{-- Выводим заказы --}}
    
    @foreach($supply_orders as $supply_order)
    
    <h5><span class="badge badge-primary">Заказ {{ $supply_order->id }}</span></h5>
    <h6>Статус <span class="badge badge-primary">{{ $supply_order->status }}</span></h6>
    <table class="table">
        <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
                <th>Кол-во позиций</th>
                <th>Кол-во товара</th>
                <th>Комментарий</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
            
                <tr>
                    <td >
                        {{-- Имя заказчика --}}
                        {{ $supply_order->creator_name }}<br>
                    </td>
                    
                    <td >
                        {{-- Дата создания --}}
                        {{ $supply_order->date_of_creation }}
                    </td>

                    <td >
                        {{-- Количество пунктов --}}
                        {{ $supply_order->entries_count }}
                    </td>
                
                    <td>
                        {{-- Количество предметов (штук) --}}
                        {{ $supply_order->items_count }}
                    </td>
                     
                   
                    
                    <td >
                        {{-- Комментарий --}}
                        {{ $supply_order->order_comment }}
                          
                    </td>

                    <td >
                        {{-- Кнопка управления --}}
                        <a href="{{ url('/employee/order/edit/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Редактировать
                            </div>
                        </a>
                    </td>
                </tr> 
        </tbody>
    </table>
    <div>
        <span>Подробности заказа</span>
    </div>
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
    <hr>
  
   @endforeach
       
    
    {{-- Конец вывода --}}
    
    @endsection