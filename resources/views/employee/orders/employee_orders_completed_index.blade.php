@extends('layouts.limitless')

@section('page_name')
    Завершенные заказы
    {{-- Возврат : кнопка --}}
    <a href="{{ url('employee/orders/index') }}" class="btn btn-info">Вернуться в заказы</a>       
@endsection

@section('content')

       
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
                        
                        {{$supply_order->item->urgency}}
                                              
                    </td>
                                   
                </tr> 
            @endforeach
        </tbody>
    </table>
    <hr>
  
   @endforeach
       
    
    {{-- Конец вывода --}}
    
    @endsection