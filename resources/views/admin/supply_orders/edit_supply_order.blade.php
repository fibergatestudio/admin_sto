@extends('layouts.limitless')

@section('page_name')
Редактировать заказ 
@endsection

@section('content')
    <h5><span class="badge badge-warning">Заказ {{ $supply_order->id }}</span></h5>
    <div class="card card-p">
        <form class="form" action="{{ url('admin/supply_orders/edit_post/'.$supply_order->id) }}" method="POST">
            @csrf

            {{-- Счётчик количества вхождений --}}
            <input type="hidden" id="counter" name="new_entries_count" value="1">

            <div>

                <label class="col-md-4" style="display: inline-block" for="item_name">Название</label>
                <label class="col-md-3" style="display: inline-block" for="item_numbers">Количество</label>
                <label class="col-md-3" style="display: inline-block" for="item_urgency">Срочность</label>
                @foreach ($supply_order->items as $supply_order->item)
                    <div v-for="id in ids" class="form-group">
                        <input type="text" name="item{{$loop->iteration}}" class="form-control col-md-4" style="display: inline-block" required id="item_name" value="{{$supply_order->item->item}}">
                        <input type="number" name="count{{$loop->iteration}}" class="form-control col-md-1" style="display: inline-block" value="{{$supply_order->item->number}}" min="1" required id="item_numbers">
                        <select name="urgency{{$loop->iteration}}" class="form-control col-md-3" style="display: inline-block" required id="item_urgency">
                            <option selected="selected" value="{{$supply_order->item->urgency}}">{{$supply_order->item->urgency}}</option>
                            <option value="Не горит">Не горит</option>
                            <option value="Горит">Горит</option>
                            <option value="Очень горит">Очень горит</option>
                        </select>
                        <input type="hidden" id="counter" name="entries_count" value="{{$loop->count}}">
                        @endforeach
                    </div>
            </div>

        <!-- <div id="app1">
            <label class="col-md-4" style="display: inline-block" for="item_name">Новые поля</label>
            <div v-for="id in ids" class="form-group">
            @{{ id.id }}.
                <input type="text" :name="'new_item'+id.id" class="form-control col-md-4" style="display: inline-block" id="item_name">
                <input type="number" :name="'new_count'+id.id" class="form-control col-md-1" style="display: inline-block" value="1" min="1" id="item_numbers">
                <select :name="'new_urgency'+id.id" class="form-control col-md-3" style="display: inline-block" id="item_urgency">
                    <option selected="selected">Выберите срочность</option>
                    <option value="Не горит">Не горит</option>
                    <option value="Горит">Горит</option>
                    <option value="Очень горит">Очень горит</option>
                </select>
            </div>

            {{-- Добавить новый элемент : кнопка --}}
                <div onclick="app1.addNewEntry()" class="btn btn-success">+</div>
                <hr>
            </div> -->



            {{-- Комментарий к заказу --}}

            <div class="form-group">
                <label for="order_comment">Комментарий к заказу</label>
            </div>
            <div class="form-group">
                <textarea rows="3" cols="107" name="order_comment" id="order_comment">{{$supply_order->order_comment}}</textarea>
            </div>
            <input type="submit" class="btn btn-primary" value="Редактировать">


        </form>
    </div>


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