@extends('layouts.limitless')

@section('page_name')
    Заказы для поставщиков
@endsection

@section('content')
    {{-- Выводим заказы --}}
    @foreach($supply_orders as $supply_order)
    <h5><span class="badge badge-primary">Заказ {{ $supply_order->id }}</span></h5>
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
                        <a href="{{ url('/admin/supply_orders/manage/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Управление
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
    <hr>
        
        <div class="row">
            {{-- Заказы для подтверждения : кнопка --}}
            <div class="col-2">
                <a href="{{ url('admin/supply_orders/worker') }}" class="btn btn-info">Заказы для подтверждения</a>
            </div>           
        </div>
    
    
@endsection