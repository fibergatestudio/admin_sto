@extends('layouts.limitless')

@section('page_name')
История финансов
@endsection

@section('content')
<div class="form-row">

{{-- Отображение всей истории финансов --}}
<div id="finances" class="card card-outline-secondary col-md-12">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">История Финансов:</h3>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="custom-control">
                        <label>Фильтры типа: </label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" class="custom-control-input" id="check1" v-model="income">
                        <label class="custom-control-label" for="check1">Начисления</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="2" class="custom-control-input" id="check2" v-model="payout">
                        <label class="custom-control-label" for="check2">Выплаты</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="3" class="custom-control-input" id="check3" v-model="fine">
                        <label class="custom-control-label" for="check3">Штрафы</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="4" class="custom-control-input" id="check4" v-model="coffee">
                        <label class="custom-control-label" for="check4">Кофе</label>
                    </div>
                </div>
                <div class="col">
                    <div v-if="sort">
                        <div v-on:click="sort = !sort"  class="btn btn-primary">Сорт.Новые</div>
                    </div>
                    <div v-if="!sort">
                        <div v-on:click="sort = !sort"  class="btn btn-info">Сорт.Старые</div>
                    </div>
                </div>
            </div>
        </form>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Тип</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                        <th>Основание</th>
                        <th>Статус</th>
                    </tr>
                </thead>
            <tbody v-if="!sort" id="tablecontents">
                {{-- Вывод выплат --}}
                    @foreach ($view_payout as $view_payout)
                    <tr v-if="payout">
                        <td>
                            {{ $view_payout->date }}
                        </td>
                        <td>
                            {{ $view_payout->type }}
                        </td>
                        <td>
                            {{ $view_payout->amount }}
                        </td>
                        <td>
                            {{ $view_payout->old_balance }}
                        </td>
                        <td>
                            {{ $view_payout->reason }}
                        </td>
                        <td>
                            {{ $view_payout->status }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Вывод заходы --}}
                    @foreach ($view_income as $view_income)
                    <tr v-if="income">
                        <td>
                            {{ $view_income->date }}
                        </td>
                        <td>
                            {{ $view_income->type }}
                        </td>
                        <td>
                            {{ $view_income->amount }}
                        </td>
                        <td>
                            {{ $view_income->old_balance }}
                        </td>
                        <td>
                            {{ $view_income->reason }}
                        </td>
                        <td>
                            {{ $view_income->status }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Вывод штрафы --}}
                    @foreach ($view_fine as $view_fine)
                    <tr v-if="fine">
                        <td>
                            {{ $view_fine->date }}
                        </td>
                        <td>
                            {{ $view_fine->type }}
                        </td>
                        <td>
                            {{ $view_fine->amount }}
                        </td>
                        <td>
                            {{ $view_fine->old_balance }}
                        </td>
                        <td>
                            {{ $view_fine->reason }}
                        </td>
                        <td>
                            {{ $view_fine->status }}
                        </td>
                    </tr>
                    @endforeach

                 {{-- Вывод штрафы --}}
                @foreach ($view_coffee as $view_coffee)
                <tr v-if="coffee">
                    <td>
                        {{ $view_coffee->date }}
                    </td>
                    <td>
                        {{ $view_coffee->type }}
                    </td>
                    <td>
                        {{ $view_coffee->amount }}
                    </td>
                    <td>
                        {{ $view_coffee->old_balance }}
                    </td>
                    <td>
                        {{ $view_coffee->reason }}
                    </td>
                    <td>
                        {{ $view_coffee->status }}
                    </td>
                </tr>
                @endforeach           
            </tbody>
            <tbody v-if="sort" id="tablecontents">
                {{-- Вывод выплат --}}
                    @foreach ($view_payout_desc as $view_payout)
                    <tr v-if="payout">
                        <td>
                            {{ $view_payout->date }}
                        </td>
                        <td>
                            {{ $view_payout->type }}
                        </td>
                        <td>
                            {{ $view_payout->amount }}
                        </td>
                        <td>
                            {{ $view_payout->old_balance }}
                        </td>
                        <td>
                            {{ $view_payout->reason }}
                        </td>
                        <td>
                            {{ $view_payout->status }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Вывод заходы --}}
                    @foreach ($view_income_desc as $view_income)
                    <tr v-if="income">
                        <td>
                            {{ $view_income->date }}
                        </td>
                        <td>
                            {{ $view_income->type }}
                        </td>
                        <td>
                            {{ $view_income->amount }}
                        </td>
                        <td>
                            {{ $view_income->old_balance }}
                        </td>
                        <td>
                            {{ $view_income->reason }}
                        </td>
                        <td>
                            {{ $view_income->status }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Вывод штрафы --}}
                    @foreach ($view_fine_desc as $view_fine)
                    <tr v-if="fine">
                        <td>
                            {{ $view_fine->date }}
                        </td>
                        <td>
                            {{ $view_fine->type }}
                        </td>
                        <td>
                            {{ $view_fine->amount }}
                        </td>
                        <td>
                            {{ $view_fine->old_balance }}
                        </td>
                        <td>
                            {{ $view_fine->reason }}
                        </td>
                        <td>
                            {{ $view_fine->status }}
                        </td>
                    </tr>
                    @endforeach

                 {{-- Вывод штрафы --}}
                @foreach ($view_coffee_desc as $view_coffee)
                <tr v-if="coffee">
                    <td>
                        {{ $view_coffee->date }}
                    </td>
                    <td>
                        {{ $view_coffee->type }}
                    </td>
                    <td>
                        {{ $view_coffee->amount }}
                    </td>
                    <td>
                        {{ $view_coffee->old_balance }}
                    </td>
                    <td>
                        {{ $view_coffee->reason }}
                    </td>
                    <td>
                        {{ $view_coffee->status }}
                    </td>
                </tr>
                @endforeach           
            </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    var finances = new Vue({
        el: '#finances',
        data: {
            income: 'true',
            payout: 'true',
            fine: 'true',
            coffee: 'true',
            sort: false
        }
    
    });
</script>

@endsection