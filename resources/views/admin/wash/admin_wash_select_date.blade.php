@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2019') }}">2019</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2020') }}">2020</a></div>
        <div class="btn col-md-3">2021</div>
        <div class="btn col-md-3">2022</div>
    </div>
    <div class="row">
        <div class="btn col-md-3">2023</div>
        <div class="btn col-md-3">2024</div>
        <div class="btn col-md-3">2025</div>
        <div class="btn col-md-3">2026</div>
    </div>

    </div>
</div>
@endsection