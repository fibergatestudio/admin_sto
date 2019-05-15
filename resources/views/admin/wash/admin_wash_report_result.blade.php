@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash/select_date') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">
    {{ $year }}
    {{ $month }}
    {{ $day }}
    
    </div>
</div>
@endsection