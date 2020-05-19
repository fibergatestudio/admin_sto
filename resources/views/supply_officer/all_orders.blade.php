@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')

<style type="text/css">
    .table-list-order-custom th, .table-list-order-custom td{
        text-align: center;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="completedModal" tabindex="-1" role="dialog" aria-labelledby="completedModalLabel" aria-hidden="true">
    <form id="form-completed" action="{{ url('/supply_officer/supply_orders/order_completed_action') }}" method="POST">
    @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completedModalLabel">Стоимость</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    {{-- ID выполненного заказа --}}
                    <input type="hidden" name="order_id" value="">
                    {{-- Наименование и количество --}}
                    <input type="hidden" name="name_quantity" value="">
                    {{-- Валюта счета --}}
                    <input type="hidden" name="account_currency" value="">
                   
                    <div class="form-row">
                        {{-- Сумма --}}
                        <div class="form-group col-md-6">
                            <label>Сумма:</label>
                            <input type="number" min="0" name="sum" class="form-control" required>
                        </div>
                        {{-- Валюта --}}
                        <div class="form-group col-md-6">
                            <label>Валюта</label>
                            <select name="currency"class="form-control">
                                <option value="MDL">MDL</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        {{-- Со счета --}}
                        <div class="form-group col-md-6">
                          <label>Со счета</label>
                          <select name="account_id" class="form-control">
                              @foreach($accounts as $account)
                              <option value="{{ $account->id }}" data-currency="{{ $account->currency }}">{{ $account->name }} {{ $account->currency }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" id="form-completed-submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</div>
    
{{-- Вывод списка всех заказов --}}
    
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
           
                    <td >
                        {{-- № заказа --}}
                        №{{ $supply_order->id }}<br>
                    </td>
                    
                    <td >
                        {{-- Имя заказчика --}}
                        {{ $supply_order->creator_name }}<br>
                    </td>
                    
                    <td >
                        {{-- Дата создания --}}
                        {{ $supply_order->date_of_creation }}
                    </td>

                    @php
                        $name_quantity = '';
                    @endphp
  
                    @foreach($supply_order->items as $supply_order->item)
                        <td >
                            {{-- Название --}}
                            {{ $supply_order->item->item }}<br>
                            @php
                                $name_quantity .= $supply_order->item->item.' ';
                            @endphp
                        </td>
                        
                        <td >
                            {{-- Количество --}}
                            {{ $supply_order->item->number }}
                            @php
                                $name_quantity .= '('.$supply_order->item->number.')';
                            @endphp
                        </td>

                        <td>
                            {{-- Срочность --}}
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
                        {{-- Наименование и количество --}}
                        <input type="hidden" name="name_quantity_text" value="{{ $name_quantity }}">
                        {{-- ID заказа --}}
                        <input type="hidden" id="counter" name="order_id_text" value="{{ $supply_order->id }}">
                        
                        <button class="w-100 my-2 btn btn-success button-completed" data-toggle="modal" data-target="#completedModal">
                            Исполнено
                        </button>
                    </td>
                </tr> 
        
            @endforeach

        </tbody>
    </table>

</div>

<!--     @foreach($supply_orders as $supply_order)
    <div class="card card-p">
        <h5><span class="badge badge-primary">Заказ {{ $supply_order->id }}</span></h5>
        <table class="table table-list-order-custom table-list-order">
            <thead>
            <tr>
                <th>Имя заказчика</th>
                <th>Дата создания</th>
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

                <td>
                    {{-- Количество предметов (штук) --}}
                    {{ $supply_order->items_count }}
                </td>

                <td>
                    {{-- Комментарий --}}
                    {{ $supply_order->order_comment }}
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

                        {{-- Кнопка управления --}}
                        <a href="{{ url('supply_officer/all_orders/view_order/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Детали и управление
                            </div>
                        </a>
                        
                        <button type="submit" class="my-1 btn btn-success">Применить</button>
                </form>
                
                @if(!empty($supply_order->order_price))
                    <form method="POST" action="{{ url('supply_officer/order_completed_action') }}">
                        @csrf
                        {{-- ID выполненного заказа --}}
                        <input type="hidden" name="order_id" value="{{ $supply_order->id }}">
                        <input type="hidden" name="items" value="{{ $supply_order->items }}">
                        <input type="hidden" name="order_price" value="{{ $supply_order->order_price }}">
                        <input type="hidden" name="payment_method" value="{{ $supply_order->payment_method }}">

                        <input type="hidden" name="user_id" value="{{ $supply_order->given_to }}">

                        <button type="submit" class="my-1 btn btn-success">
                            Исполнено
                        </button>
                    </form>
                    @endif
                    
                    </td>
            </tr>
            </tbody>
        </table>
        <div>
            <hr class="mt-1">
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
                        {{-- Срочность --}}
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
@endforeach -->
    

    {{-- Конец вывода заказов --}}

    <script type="text/javascript">
        
        // Передача данных о заказе при нажатии на Исполнено
        $('.button-completed').click(function () { 
            $('[name="order_id"]').val($('[name="order_id_text"]').val());
            $('[name="name_quantity"]').val($('[name="name_quantity_text"]').val());
        });
        
        $('#form-completed-submit').click(function () { 
            if (!$('[name="sum"]').val()) {
                alert('Заполните сумму !');
                return 0;
            }
            $('[name="account_currency"]').val($('select[name="account_id"] option:selected').attr('data-currency'));     
            $('#form-completed').submit();
        });

    </script>

@endsection

