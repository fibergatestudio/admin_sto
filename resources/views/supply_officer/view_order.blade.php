@extends('layouts.limitless')

@section('page_name')
Просмотр заказа № {{ $supply_order->id }}
    <hr>
    {{-- Вернуться : кнопка --}}
    <a href="{{ url('supply_officer/all_orders/list') }}">
        <div class="btn btn-danger">
            К списку активных заказов
        </div>
    </a>
@endsection

@section('content')
    {{-- Вывод основной информации по заказу --}}
    
        {{-- Имя заказчика --}}
        Имя заказчика: {{ $supply_order->creator_name }}<br>
        
        {{-- Дата создания в виде ДД.ММ.ГГГГ --}}
        Дата создания: {{ $supply_order->date_of_creation }}<br>

        {{-- Статус --}}
        Статус заказа:
        @if($supply_order->status == 'active')
            <span class="badge badge-primary">Активный</span>
        @elseif($supply_order->status == 'completed')
            <span class="badge badge-success">Выполненный</span>
        @endif
        

    <hr>
    {{-- Вывод деталей по заказу --}}
    Список заказанного:
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <!--<th>Описание</th>-->
                <th>Количество</th>
                <th>Срочность</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supply_order_items as $item)
                <tr>
                    <td>
                        {{-- Название --}}
                        {{ $item->item }}
                    </td>

                    <!--<td>
                        {{-- Описание --}}
                        {{ $item->description }}
                    </td>-->

                    <td>
                        {{-- Количество --}}
                        {{ $item->number }}
                    </td>
                    
                    <td>
                        {{-- Срочност --}}
                        @if($item->urgency == 'Не горит')
                        <span class="badge badge-success">{{$item->urgency}}</span>
                        @elseif($item->urgency == 'Горит')
                        <span class="badge badge-warning">{{$item->urgency}}</span>
                        @elseif($item->urgency == 'Очень горит')
                        <span class="badge badge-danger">{{$item->urgency}}</span>
                        @endif                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Конец вывода деталей по заказу --}}

    <hr>
    {{-- Заказ выполнен : Кнопка --}}
    @if($supply_order->status == 'active')
        <form method="POST" action="{{ url('supply_officer/order_completed_action') }}">
            @csrf
            {{-- ID выполненного заказа --}}
            <input type="hidden" name="order_id" value="{{ $supply_order->id }}">

            <button type="submit" class="btn btn-success">
                Заказ выполнен
            </button>
        </form>
    @endif

    
    
@endsection