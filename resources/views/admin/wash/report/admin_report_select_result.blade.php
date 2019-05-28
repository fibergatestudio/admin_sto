@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash/report/'.$year.'/'.$month) }}"><button class="btn btn-warning">Назад</button></a>
    <a href="{{ url('/admin/wash/report/') }}"><button class="btn btn-primary">Изменить Год</button></a>
    <a href="{{ url('/admin/wash/report/'.$year) }}"><button class="btn btn-primary">Изменить Месяц</button></a>
    <a href="{{ url('/admin/wash/report/'.$year.'/'.$month) }}"><button class="btn btn-primary">Изменить День</button></a>
@endsection

@section('content')
<div class="form-row">
    <b>Текущая дата: {{ $year }} {{ $month }} {{ $day }}</b><br>
    <div class="card card-outline-secondary col-md-12">
    <hr>
        <table id="table" class="table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Клиент</th>
                    <th>Кол-во моек</th>
                    <th>Сумма</th>
                </tr>
            </thead>
        <tbody >
        @foreach ($car_wash_report_table as $wash_report)
            <tr>
                <th>{{ $wash_report->id }}</th>
                <th>{{ $wash_report->firm_name }}</th>
                <th></th>
                <th>{{ $wash_report->payment_sum }}</th>
            </tr>
        @endforeach
                <tr>
                    <th>Всего</th>
                    <th>------</th>
                    <th></th>
                    <th></th>
                </tr> 
        </tbody>
        </table>
    </div>
</div>
@endsection