@extends('layouts.limitless')

@section('page_name')
    Подтвержденные заказы
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
                <th>Название товара</th>
                <th>Количество</th>
                <th>Срочность</th>  
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

                    <!-- <td >
                        {{-- Количество пунктов --}}
                        {{ $supply_order->entries_count }}
                    </td> -->
                
                    <!-- <td>
                        {{-- Количество предметов (штук) --}}
                        {{ $supply_order->items_count }}
                    </td> -->
                     
                   
                    
                    <td >
                        {{-- Комментарий --}}
                        {{ $supply_order->order_comment }}
                          
                    </td>

                    <!-- <td >
                        {{-- Кнопка управления --}}
                        <a href="{{ url('/employee/orders/edit/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Редактировать
                            </div>
                        </a>
                    </td> -->
                </tr> 
        </tbody>
    </table>
    <br>
    <hr>
   @endforeach
       
    
    {{-- Конец вывода --}}
    
    @endsection