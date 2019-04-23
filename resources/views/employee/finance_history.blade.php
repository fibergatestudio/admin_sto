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
        <!-- Пойдет? да, норм. слева новое, справа старое? + -->
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
                    @foreach($all_logs_asc as $all_logs_asc_entry)
                        <tr v-if="{{ $all_logs_asc_entry->eng_type }}">
                                <td>
                                    {{ $all_logs_asc_entry->date }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->type }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->amount }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->old_balance }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->reason }}
                                </td>
                                <td>
                                    {{ $all_logs_asc_entry->status }}
                                </td>
                            </tr>
                    @endforeach
                </tbody>
                <tbody v-if="sort" id="tablecontents">
                @foreach($all_logs_desc as $all_logs_desc_entry)
                        <tr v-if="{{ $all_logs_desc_entry->eng_type }}">
                                <td>
                                    {{ $all_logs_desc_entry->date }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->type }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->amount }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->old_balance }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->reason }}
                                </td>
                                <td>
                                    {{ $all_logs_desc_entry->status }}
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
            test: 'admin',
            sort: false
        }

    });
</script>

@endsection
