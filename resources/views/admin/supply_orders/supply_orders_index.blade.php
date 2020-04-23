@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')


<!-- Modal -->
<div class="modal fade" id="completedModal" tabindex="-1" role="dialog" aria-labelledby="completedModalLabel" aria-hidden="true">
    <form id="form-completed" action="{{ url('/admin/supply_orders/order_completed_action') }}" method="POST">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</div>


{{-- Выводим заказы --}}
<div class="card card-p">
    <form class="form" action="{{ url('admin/supply_orders/new') }}" method="POST">
        @csrf

        {{-- Счётчик количества вхождений --}}
        <input type="hidden" id="counter" name="entries_count" value="0">


        <div id="app1">
            <label class="col-md-1" style="display: inline-block" for="item_name"></label>
            <label class="col-md-3" style="display: inline-block" for="item_name">Название</label>
            <label class="col-md-2" style="display: inline-block" for="item_numbers">Количество</label>
            <label class="col-md-2" style="display: inline-block" for="item_urgency">Срочность</label>
            <label class="col-md-2" style="display: inline-block" for="item_urgency">Комментарий к заказу</label>
            <!-- <label for="order_comment">Комментарий к заказу</label> -->
            <div v-for="(id, index) in ids" class="form-group">
                @{{ id.id + 1 }}.
                <div @click="removeNewEntry(index)" class="btn btn-warning m-1">-</div>
                <input type="text" :name="'item'+index" class="form-control col-md-3" style="display: inline-block" required id="item_name">
                <input type="number" :name="'count'+index" class="form-control col-md-2" style="display: inline-block" value="1" min="1" required id="item_numbers">
                <select :name="'urgency'+index" class="form-control col-md-2" style="display: inline-block" required id="item_urgency">
                    <option value="Не горит" selected="selected">Не горит</option>
                    <option value="Горит">Горит</option>
                    <option value="Очень горит">Очень горит</option>
                </select>
                <!-- <input type="text" class="form-control col-md-2" style="display: inline-block" > -->
                <input type="text" :name="'order_comment'+index" class="form-control col-md-3" style="display: inline-block" id="order_comment">
                {{-- Комментарий к заказу --}}
                <!-- <div class="form-group p-2">
                    <textarea class="form-control col-md-2" rows="3" cols="45" :name="'order_comment'+index" id="order_comment" placeholder="Введите коментарий к заказу"></textarea>
                </div> -->
            </div>
            {{-- Добавить новый элемент : кнопка --}}
            <div class="row pl-3">
                <div onclick="app1.addNewEntry()" class="btn btn-success m-1">+</div>
                <!-- <div @click="removeNewEntry(index)" class="btn btn-warning m-1">-</div> -->
                <!-- <div onclick="app1.removeNewEntry()" class="btn btn-warning m-1">remove</div> -->
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
</div>

    <div class="row">
            {{-- Заказы для подтверждения : кнопка --}}
            <div class="col-3">
                <a href="{{ url('admin/supply_orders/worker') }}" class="w-100 btn btn-info">Заказы для подтверждения</a>
            </div>     
            {{-- Архив : переход --}}
        <div class="col-3">
            <a href="{{ url('admin/supply_orders/archive') }}">
                <div class="btn btn-light w-100">
                    Архив
                </div>
            </a>
        </div>

        </div>
    <hr>


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
    
            @if($supply_order->status === 'active')
                <tr style="background-color: white;">
            @else
                <tr style="background-color: wheat;">
            @endif        
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
                        {{-- Кнопка управления --}}
                        <a href="{{ url('/admin/supply_orders/manage/'.$supply_order->id) }}">
                            <div class="w-100 btn btn-primary">
                                Управление
                            </div>
                        </a>
                        <input type="hidden" name="order_id_table" value="{{ $supply_order->id }}">
                        <button class="w-100 my-2 btn btn-success button-completed" data-toggle="modal" data-target="#completedModal">
                            Исполнено
                        </button>
                    </td>
                </tr> 
        
            @endforeach

        </tbody>
    </table>
    <hr>
        
    
    {{-- Конец вывода --}}
        
        <div class="row">
            {{-- Заказы для подтверждения : кнопка --}}
            <div class="col-3">
                <a href="{{ url('admin/supply_orders/worker') }}" class="w-100 btn btn-info">Заказы для подтверждения</a>
            </div>     
            {{-- Архив : переход --}}
            <div class="col-3">
                <a href="{{ url('admin/supply_orders/archive') }}">
                    <div class="w-100 btn btn-light">
                        Архив
                    </div>
                </a>
            </div>

        </div>
    
    
        <script>
        var currentCounter = 0;

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
                removeNewEntry: function(index){
                    currentCounter = currentCounter - 1;
                    this.ids.splice(index, 1);
                    document.getElementById("counter").value = currentCounter;
                },
            }
        });


        $('.button-completed').click(function () {
            let val = $(this).siblings('input').val();
            $('[name="order_id"]').val(val);
            let action = $('#form-completed').attr('action');
            $('#form-completed').attr('action', action+'/'+val);
        });
    </script>
@endsection