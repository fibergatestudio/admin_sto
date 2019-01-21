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
                <th>Комментарий к заказу</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        @foreach($archived_orders as $supply_order)
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
                        <span class="urgency">{{ $supply_order->item->urgency }}</span>
                    </td>
                                   
                </tr> 
            @endforeach
        </tbody>
    @endforeach
    
    
    {{--Выделение цветом --}}
    <script>
        $(document).ready(function() {
            var urgency = $('.urgency').text();
            console.log(urgency);
            if (urgency == "Не горит"){
                $('.urgency').addClass("badge badge-success");
            }
            if (urgency == "Горит"){
                $('.urgency').addClass("badge badge-warning");
            }
            if (urgency == "Очень горит"){
                $('.urgency').addClass("badge badge-danger");
            }
           
	})	
    </script>
@endsection