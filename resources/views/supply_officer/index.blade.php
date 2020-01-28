@extends('layouts.limitless')

@section('page_name')
    Снабженец: главная страница
@endsection

@section('content')
    Вы можете перейти на страницу <a href="{{ url('supply_officer/all_orders/list') }}">заказов на снабжение</a>.
@endsection