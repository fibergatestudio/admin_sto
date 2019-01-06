@extends('layouts.limitless')

@section('page_name')
Добавить новый заказ
@endsection

@section('content')
    <form class="form" action="{{ url('admin/supply_orders/new') }}" method="POST">
        @csrf

        {{-- Счётчик количества вхождение --}}
        <input id="counter" type="hidden" name="entries_count" value="1">


        <div id="app1">
            
            <div v-for="id in ids" class="form-group">
                @{{ id.id }}.
                <input type="text" :name="'item'+id.id" class="form-control col-md-4" style="display: inline-block" required>
                <input type="number" :name="'count'+id.id" class="form-control col-md-1" style="display: inline-block" value="1" min="1" required>
            </div>
            
            {{-- Добавить новый элемент : кнопка --}}
            <div onclick="app1.addNewEntry()" class="btn btn-success">+</div>
            <hr>
        </div>

        <button type="submit" class="btn btn-primary">
            Сохранить
        </button>
    </form>


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

                }
            }
        });
    </script>

@endsection