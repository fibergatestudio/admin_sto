<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница общей рентабельности
@endsection

@section('content')
    
    {{-- Курс валют --}}
    <h2>Курс валют</h2>
    <form method="POST" action="{{ url('/admin/profitability_index') }}">
        @csrf
        <div class="row">            
            <div class="col-md-2">
                <div class="form-group">
                    <label>MDL</label>
                    <input type="number" name="mdl_currency" class="form-control" value="1" disabled="disabled">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>USD</label>
                    <input type="number" name="usd_currency" class="form-control" value="{{ $usd }}" step="0.00001">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>EUR</label>
                    <input type="number" name="eur_currency" class="form-control" value="{{ $eur }}" step="0.00001">
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    <hr>

    {{-- Ежемесячные расходы --}}
    <h2>Ежемесячные расходы</h2>
    <form method="POST" action="{{ url('/admin/profitability_index/month') }}">
        @csrf
        <div class="row">            
            <div class="col-md-2">
                <div class="form-group">
                    <label>Стоимость аренды</label>
                    <input type="number" name="rental_price" class="form-control" step="0.01">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Электричество</label>
                    <input type="number" name="electricity" class="form-control" step="0.01">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Водоснабжение</label>
                    <input type="number" name="water_supply" class="form-control" step="0.01">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" min="2000-01-01" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    <hr>

    {{-- Расчет рентабельности --}}
    <h2>Расчет рентабельности</h2>
    
    {{-- Вывод списока расходов и доходов --}}
    <table class="table">
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Основание</th>
                <th>Валюта</th>
            </tr>
        </thead>
        <tbody>
        <?php $sum = 0;?>
        @foreach($assignment_income as $income_entry)
        <?php
        if ($income_entry->currency === 'USD') {
            $sum += round(($income_entry->amount)/$usd);
        }
        else if ($income_entry->currency === 'EUR') {
            $sum += round(($income_entry->amount)/$eur);
        }
        else {
            $sum += $income_entry->amount;
        } 
        ?>
        <tr>
            <td>
            {{ $income_entry->amount }}<br>
            </td>
            <td>
            {{ $income_entry->basis }}<br>
            </td>
            <td>
            {{ $income_entry->currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($zonal_assignment_income as $income_entry)
        <?php
        if ($income_entry->zonal_currency === 'USD') {
            $sum += round(($income_entry->zonal_amount)/$usd);
        }
        else if ($income_entry->zonal_currency === 'EUR') {
            $sum += round(($income_entry->zonal_amount)/$eur);
        } 
        else {
            $sum += $income_entry->zonal_amount;
        } 
        ?>
        <tr>
            <td>
            {{ $income_entry->zonal_amount }}<br>
            </td>
            <td>
            {{ $income_entry->zonal_basis }}<br>
            </td>
            <td>
            {{ $income_entry->zonal_currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($assignment_expense as $expense_entry)
        <?php
        if ($expense_entry->currency === 'USD') {
            $sum -= round(($expense_entry->amount)/$usd);
        }
        else if ($expense_entry->currency === 'EUR') {
            $sum -= round(($expense_entry->amount)/$eur);
        } 
        else {
            $sum -= $expense_entry->amount;
        } 
        ?>
        <tr>
            <td>- 
            {{ $expense_entry->amount }}<br>
            </td>
            <td>
            {{ $expense_entry->basis }}<br>
            </td>
            <td>
            {{ $expense_entry->currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($zonal_assignment_expense as $expense_entry)
        <?php
        if ($expense_entry->zonal_currency === 'USD') {
            $sum -= round(($expense_entry->zonal_amount)/$usd);
        }
        else if ($expense_entry->zonal_currency === 'EUR') {
            $sum -= round(($expense_entry->zonal_amount)/$eur);
        }  
        else {
            $sum -= $expense_entry->zonal_amount;
        } 
        ?>
        <tr>
            <td>- 
            {{ $expense_entry->zonal_amount }}<br>
            </td>
            <td>
            {{ $expense_entry->zonal_basis }}<br>
            </td>
            <td>
            {{ $expense_entry->zonal_currency }}<br>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <h4 id="total">Итого: {{ $sum }} лей</h4>
    
@endsection