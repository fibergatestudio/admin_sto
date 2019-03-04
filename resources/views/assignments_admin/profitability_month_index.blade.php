<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница месячной рентабельности
@endsection

@section('content')   

    {{-- Ежемесячные расходы --}}
    <h2>Ежемесячные расходы</h2>
        <div class="row">            
            <div class="col-md-2">
                <div class="form-group">
                    <label>Стоимость аренды</label>
                    <input type="number" name="rental_price" class="form-control" value="{{ $rental_price }}" disabled="disabled">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Электричество</label>
                    <input type="number" name="electricity" class="form-control" value="{{ $electricity }}" disabled="disabled">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Водоснабжение</label>
                    <input type="number" name="water_supply" class="form-control" value="{{ $water_supply }}" disabled="disabled">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" id="our-date" name="date" value="{{ date('Y-m-d', strtotime($date)) }}" min="2000-01-01" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
        
        <button onclick="showProfitability()" class="btn btn-primary">Показать</button>
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
        @if(substr($date,0,-3) === substr($income_entry->updated_at,0,7))
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
        @endif
        @endforeach
        @foreach($zonal_assignment_income as $income_entry)
        @if(substr($date,0,-3) === substr($income_entry->updated_at,0,7))
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
        @endif
        @endforeach
        @foreach($assignment_expense as $expense_entry)
        @if(substr($date,0,-3) === substr($expense_entry->updated_at,0,7))
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
        @endif
        @endforeach
        @foreach($zonal_assignment_expense as $expense_entry)
        @if(substr($date,0,-3) === substr($expense_entry->updated_at,0,7))
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
        @endif
        @endforeach
        <tr>
            <td>- 
            {{ $rental_price }}<br>
            </td>
            <td>
            {{ 'Стоимость аренды' }}<br>
            </td>
            <td>
            {{ 'MDL' }}<br>
            </td>
        </tr>
        <tr>
            <td>- 
            {{ $electricity }}<br>
            </td>
            <td>
            {{ 'Электричество' }}<br>
            </td>
            <td>
            {{ 'MDL' }}<br>
            </td>
        </tr>
        <tr>
            <td>- 
            {{ $water_supply }}<br>
            </td>
            <td>
            {{ 'Водоснабжение' }}<br>
            </td>
            <td>
            {{ 'MDL' }}<br>
            </td>
        </tr>
        <?php
            $sum -= $rental_price + $electricity + $water_supply;
        ?>
        </tbody>
    </table>
    <h4 id="total">Итого: {{ $sum }} лей</h4>

    <script type="text/javascript">
        var rootSite = '<?=URL::to('/')?>';
        function showProfitability(){
            let value = document.getElementById('our-date').value;
            window.location.href = rootSite + '/admin/profitability_index/month/'+ value;
        }
    </script>
    
@endsection