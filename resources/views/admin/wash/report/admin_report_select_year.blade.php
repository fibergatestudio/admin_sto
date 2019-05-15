@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')

<?php 

$year = date('Y');

?>

<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2019') }}">2019</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2020') }}">2020</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2021') }}">2021</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2022') }}">2022</a></div>

    </div>
    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2023') }}">2023</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2024') }}">2024</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2025') }}">2025</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/report/2026') }}">2026</a></div>
    </div>

    </div>
</div>
@endsection