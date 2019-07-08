@extends('layouts.limitless')

@section('page_name')
    Заказы для поставщиков1
@endsection

@section('content')

{{-- Выводим заказы --}}

    <form class="form" action="{{ url('employee/order/new_post') }}" method="POST">
        @csrf

        {{-- Счётчик количества вхождений --}}
        <input type="hidden" id="counter" name="entries_count" value="1">

        {{-- Строки под элементы заказа на Vue.JS --}}
        <div id="app1">
            <label class="col-md-4" style="display: inline-block" for="item_name">Название</label>
            <label class="col-md-1" style="display: inline-block" for="item_numbers">Количество</label>
            <label class="col-md-2" style="display: inline-block" for="item_urgency">Срочность</label>
            <label for="order_comment">Комментарий к заказу</label>
            <div v-for="id in ids" class="form-group">
                @{{ id.id }}.
                <input type="text" :name="'item'+id.id" class="form-control col-md-4" style="display: inline-block" required id="item_name">
                <input type="number" :name="'count'+id.id" class="form-control col-md-1" style="display: inline-block" value="1" min="1" required id="item_numbers">
                <select :name="'urgency'+id.id" class="form-control col-md-3" style="display: inline-block" required id="item_urgency">
                    <option selected="selected">Выберите срочность</option>
                    <option value="Не горит">Не горит</option>
                    <option value="Горит">Горит</option>
                    <option value="Очень горит">Очень горит</option>
                </select>
            </div>
            {{-- Комментарий к заказу --}}
            <div class="form-group col-md-8">
                <textarea class="form-control" rows="3" cols="45" name="order_comment" id="order_comment" placeholder="Введите коментарий к заказу"></textarea>
            </div>
            {{-- Добавить новый элемент : кнопка --}}
            <div class="row">
                <div onclick="app1.addNewEntry()" class="btn btn-success m-1">+</div>
                <button type="submit" class="btn btn-primary m-1">
                    Добавить
                </button>
            </div>
            <hr>
        
            <!-- <div class="form-group">
                <label for="order_comment">Комментарий к заказу</label>
            </div>
            <div class="form-group">
            <textarea rows="3" cols="45" name="order_comment" id="order_comment" placeholder="Введите коментарий к заказу"></textarea>
            </div> -->
        </div>
                 
    </form>
    <br>
    <hr>


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
                        @if($supply_order->creator_id != $curr_user_id)
                        <a href="{{ url('/admin/supply_orders/manage/'.$supply_order->id) }}">
                            <div class="btn btn-primary">
                                Управление
                            </div>
                        </a>
                        @else
                        <b>Нельзя Редактировать</b>
                        @endif
                        <!-- <form method="POST" action="{{ url('/admin/supply_orders/'.$supply_order->id.'/order_completed_action') }}">
                            @csrf
                            {{-- ID выполненного заказа --}}
                            <input type="hidden" name="order_id" value="{{ $supply_order->id }}">

                            <button type="submit" class="btn btn-success">
                                Исполнено
                            </button>
                        </form> -->
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
        <div class="row">
            {{-- Новый заказ : кнопка --}}
            <div class="col-2">
                <a href="{{ url('employee/orders/new') }}" class="btn btn-success">Новый заказ</a>
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
                        <a href="{{ url('/employee/orders/edit/'.$supply_order->id) }}">
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

    <script>
        var currentCounter = 1;

        var app1 = new Vue({
            el: '#app1',
            data: {
                ids: [
                    { id: currentCounter},
                ]
            },
            methods: {
                addNewEntry: function(){
                    currentCounter = currentCounter + 1;
                    this.ids.push({id: currentCounter});
                    document.getElementById("counter").value = currentCounter;
                },
            }
        });
    </script>
    
    @endsection