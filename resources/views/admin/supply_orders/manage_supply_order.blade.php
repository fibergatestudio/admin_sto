@extends('layouts.limitless')

@section('page_name')
    Управление заказом {{ $supply_order->id }}
@endsection

@section('content')
    {{-- Непосредственно управление заказов --}}
    {{-- ... ---}}

    {{-- Архивировать : кнопка --}}
    <a href="{{ url('/admin/supply_orders/archive/'.$supply_order->id) }}">
        <div class="btn btn-danger">
            Архивировать
        </div>
    </a>

    {{-- Вернуться : кнопка --}}
    <a href="{{ url('admin/supply_orders/index') }}">
        <div class="btn btn-default">
            Вернуться
        </div>
    </a>
@endsection