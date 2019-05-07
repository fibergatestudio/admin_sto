@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash/select_date') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/01') }}">Январь</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/'. $year . '/02') }}">Февраль</a></div>
        <div class="btn col-md-3">Март</div>
        <div class="btn col-md-3">Апрель</div>
    </div>
    <div class="row">
        <div class="btn col-md-3">Май</div>
        <div class="btn col-md-3">Июнь</div>
        <div class="btn col-md-3">Июль</div>
        <div class="btn col-md-3">Август</div>
    </div>
    <div class="row">
        <div class="btn col-md-3">Сентябрь</div>
        <div class="btn col-md-3">Октябрь</div>
        <div class="btn col-md-3">Ноябрь</div>
        <div class="btn col-md-3">Декабрь</div>
    </div>

    </div>
</div>
@endsection