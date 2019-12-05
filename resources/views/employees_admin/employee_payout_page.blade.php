@extends('layouts.limitless')

@section('page_name')
Страница выплат по сотруднику: {{ $employee->name }}

{{-- Вернуться : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>

@endsection

@section('content')
    {{-- Форма выплатить вручную --}}
    <div id="graph">
        <h4>Выплатить вручную</h4>
    <div class="card card-p">
        <form action="{{ url('/supervisor/employee_finances/payout/'.$employee->id.'/apply_payout') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <input type="number" name="payout_balance" min="1" class="py-1 mr-2">

            <button type="submit" class="btn btn-success">Выплатить</button>

        </form>
    </div>

        <hr>
        <div class="d-flex align-items-baseline">
            <h4 class="mr-2">Текущий баланс:&nbsp;</h4>
            <b style="font-size: 16px;" class="btn btn-success"> {{ $employee->balance}}</b>
        </div>
        <hr>
        {{-- История выплат (последние 10) --}}
        <h4>История выплат (последние 10 операций)</h4>
            <!-- {{-- Кнопки Отображения Графика --}}
            <div v-if="!payout_graph" v-on:click="payout_graph = !payout_graph" class="btn btn-info">
                Показать график
            </div>
            <div v-if="payout_graph" v-on:click="payout_graph = !payout_graph" class="btn btn-dark">
                Скрыть график
            </div> -->
            {{-- График Выплат --}}
        <div class="card card-p">
            <div v-if="payout_graph" >
               <h4 class="text-center">Тест график</h4>
                <div style="max-width:600px; max-height: 400px; margin: 0 auto;">
                    {!! $chart->container() !!}
                    {!! $chart->script() !!}
                </div>
            </div>
        </div>

        <div class="card card-p">
            <table class="table">
                <thead>
                <th>Сумма</th>
                <th>Остаток</th>
                <th>Дата</th>
                <th>Причина</th>
                </thead>
                <tbody>
                @foreach($employee_payout as $employee_payout)
                    <tr>
                        <td>{{ $employee_payout->amount}}</td>
                        <td>{{ $employee_payout->old_balance}}</td>
                        <td>{{ $employee_payout->date}}</td>
                        <td>{{ $employee_payout->reason}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script> -->
<!-- <script>
var graph = new Vue({
    el: '#graph',
    data: {
        payout_graph: false,
    }

});
</script> -->

@endsection