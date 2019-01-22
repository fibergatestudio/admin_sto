@extends('layouts.limitless')

@section('page_name')
Редактировать заказ 
@endsection

@section('content')
    <h5><span class="badge badge-warning">Заказ {{ $supply_order->id }}</span></h5>
    <form class="form" action="{{ url('admin/supply_orders/edit_post/'.$supply_order->id) }}" method="POST">
        @csrf
        
        <div>
            
            <label class="col-md-4" style="display: inline-block" for="item_name">Название</label>
            <label class="col-md-1" style="display: inline-block" for="item_numbers">Количество</label>
            <label class="col-md-2" style="display: inline-block" for="item_urgency">Срочность</label>
            @foreach ($supply_order->items as $supply_order->item)
            <div>                
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
            
            
                 
        {{-- Комментарий к заказу --}}
        
        <div class="form-group">
            <label for="order_comment">Комментарий к заказу</label>
        </div>
        <div class="form-group">
            <textarea rows="3" cols="45" name="order_comment" id="order_comment">{{$supply_order->order_comment}}</textarea>
        </div>
        <input type="submit" class="btn btn-primary" value="Редактировать">
            
        
    </form>



@endsection