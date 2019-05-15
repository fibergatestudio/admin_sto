@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url()->previous() }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')

<?php 

$month = date('m');

echo $month;

?>

<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/01') }}">Январь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/02') }}">Февраль</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/03') }}">Март</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/04') }}">Апрель</a></div>

    </div>
    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/05') }}">Май</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/06') }}">Июнь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/07') }}">Июль</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/08') }}">Август</a></div>
    </div>
    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/09') }}">Сентябрь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/10') }}">Октябрь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/11') }}">Ноябрь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/12') }}">Декабрь</a></div>
    </div>

    </div>
</div>
@endsection