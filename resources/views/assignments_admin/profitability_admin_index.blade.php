<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>

@extends('layouts.limitless')

@section('page_name')
    Страница общей рентабельности    
    <a href="{{ url('/admin/profitability/profitability_index/month') }}" class="btn btn-success">Просмотреть ежемесячную рентабельность</a>
@endsection

@section('content')
    
    {{-- Курс валют --}}
    <h2>Курс валют</h2>
    <h4>Текущий Курс(тест)</h4>
    <div class="card card-p">
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
                    <input type="number" name="usd_currency" class="form-control" value="{{ $exchange_rates->usd }}" disabled>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>EUR</label>
                    <input type="number" name="eur_currency" class="form-control" value="{{ $exchange_rates->eur }}" disabled>
                </div>
            </div>
            <form method="POST" action="{{ url('/admin/profitability/profitability_index') }}">
                @csrf
                <div class="row pl-2">
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

                <button type="submit" class="ml-2 btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>



    {{-- Ежемесячные расходы --}}
    <h2>Ежемесячные расходы</h2>
    <p>*Если в выбранный месяц расходы уже вводились, то данные обновятся.</p>
    <div class="card card-p">
        <form method="POST" action="{{ url('/admin/profitability/profitability_index/month') }}">
            @csrf
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Стоимость аренды</label>
                        <input type="number" name="rental_price" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Электричество</label>
                        <input type="number" name="electricity" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Водоснабжение</label>
                        <input type="number" name="water_supply" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Газ</label>
                        <input type="number" name="gas" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Уборка</label>
                        <input type="number" name="cleaning" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Вывоз мусора</label>
                        <input type="number" name="garbage_removal" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Прочие расходы</label>
                        <input type="number" name="other_expenses" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Дата</label>
                        <input class="form-control" type="date" name="date" value="{{ date('Y-m-d') }}" min="2000-01-01" max="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
        <hr>
    </div>


    {{-- Расчет рентабельности --}}
    <h2>Расчет общей рентабельности</h2>
    <div class="card card-p">
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
            @if(isset($assignment_income))
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
            @endif
            @if(isset($assignment_expense))
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
            @endif
            @if(isset($profitability_months))
                @foreach($profitability_months as $profitability_month)
                    <?php
                    $sum -= $profitability_month->rental_price + $profitability_month->electricity + $profitability_month->water_supply + $profitability_month->gas + $profitability_month->cleaning + $profitability_month->garbage_removal + $profitability_month->other_expenses;
                    ?>
                    <tr>
                        <td>-
                            {{ $profitability_month->rental_price }}<br>
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
                            {{ $profitability_month->electricity }}<br>
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
                            {{ $profitability_month->water_supply }}<br>
                        </td>
                        <td>
                            {{ 'Водоснабжение' }}<br>
                        </td>
                        <td>
                            {{ 'MDL' }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td>-
                            {{ $profitability_month->gas }}<br>
                        </td>
                        <td>
                            {{ 'Газ' }}<br>
                        </td>
                        <td>
                            {{ 'MDL' }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td>-
                            {{ $profitability_month->cleaning }}<br>
                        </td>
                        <td>
                            {{ 'Уборка' }}<br>
                        </td>
                        <td>
                            {{ 'MDL' }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td>-
                            {{ $profitability_month->garbage_removal }}<br>
                        </td>
                        <td>
                            {{ 'Вывоз мусора' }}<br>
                        </td>
                        <td>
                            {{ 'MDL' }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td>-
                            {{ $profitability_month->other_expenses }}<br>
                        </td>
                        <td>
                            {{ 'Прочие расходы' }}<br>
                        </td>
                        <td>
                            {{ 'MDL' }}<br>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <h4 id="total" class="mt-3">Итого: {{ $sum }} лей</h4>
    </div>

    
@endsection