@extends('layouts.limitless')

@section('page_name')
Добавить новый заказ
 <a href="{{ url('employee/orders/index') }}" class="btn btn-info">Вернуться в заказы</a>
  
@endsection

@section('content')
    <form class="form" action="{{ url('employee/order/new_post') }}" method="POST">
        @csrf

        {{-- Счётчик количества вхождений --}}
        <input type="hidden" id="counter" name="entries_count" value="1">

        {{-- Срочность 
        
        <div class="form-group">
            <label>Срочность заказа: </label>
            <select name="urgency">
                <option value="1">1</option>
                <option value="1">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>--}}

        {{-- Строки под элементы заказа на Vue.JS --}}
        <div id="app1">
            <label class="col-md-4" style="display: inline-block" for="item_name">Название</label>
            <label class="col-md-1" style="display: inline-block" for="item_numbers">Количество</label>
            <label class="col-md-2" style="display: inline-block" for="item_urgency">Срочность</label>
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
            
            {{-- Добавить новый элемент : кнопка --}}
            <div onclick="app1.addNewEntry()" class="btn btn-success">+</div>
            <hr>
        </div>
                 
        {{-- Комментарий к заказу --}}
        
        <div class="form-group col-md-8 p-2">
            <label for="order_comment">Комментарий к заказу</label>
        </div>
        <div class="form-group">
        <textarea rows="3" cols="45" name="order_comment" id="order_comment" placeholder="Введите коментарий к заказу"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">
            Сохранить
        </button>
    </form>
    <hr>
    

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