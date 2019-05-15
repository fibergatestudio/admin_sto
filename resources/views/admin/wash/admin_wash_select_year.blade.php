@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')

<?php 

$year = date('Y');

echo $year;

?>

<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2019') }}">2019</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2020') }}">2020</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2021') }}">2021</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2022') }}">2022</a></div>

    </div>
    <div class="row">
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2023') }}">2023</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2024') }}">2024</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2025') }}">2025</a></div>
        <div class="btn col-md-3"><a href="{{ url('/admin/wash/select_date/2026') }}">2026</a></div>
    </div>

    </div>
</div>
@endsection