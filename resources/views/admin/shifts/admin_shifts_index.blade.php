@extends('layouts.limitless')

@section('page_name')
    Все смены
@endsection

@section('content')
    <h2>Смены</h2>
    

    
    <table class="table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Начало смены</th>
                <th>Конец смены</th>
                <th>Примечание</th>
                <th>Сумма выплаты</th>
                <th>Выплата</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
        @foreach($shifts as $shift)
        <tr>
            <td>
            {{ $shift->shift_emp_name }}<br>
            </td>
            <td>
            {{ $shift->status }}<br>
            </td>
            <td>
            {{ $shift->date }}<br>
            </td>
            <td>
            {{ $shift->opened_at }}<br>
            </td>
            <td>
            {{ $shift->closed_at }}<br>
            </td>
            <td>
            {{ $shift->note }}<br>
            </td>
            <td>
            {{ $shift->payment_amount }}<br>
            </td>
            <td>
            {{ $shift->payment_status }}<br>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    
    
@endsection