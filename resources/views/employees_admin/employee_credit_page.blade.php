@extends('layouts.limitless')

@section('page_name')
Страница начислений по сотруднику: {{ $employee->name }}

{{-- Вернуться : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection

@section('content')
    {{-- Форма начислить вручную --}}
    <h4>Пополнить вручную</h4>
    <div class="card card-p">
        <form action="{{ url('/supervisor/employee_finances/credit/'.$employee->id.'/add_balance') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            @if($errors->any())
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>Ошибка! </strong>{{$errors->first()}}
                </div>
            @endif
            <div class="row my-2">
                <div class="col-lg-1">
                    <label>Сумма</label>
                </div>
                <div class="col-lg-11">
                    <input class="form-control" type="number" name="balance" min="1" required>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-lg-1">
                    <label>Причина</label>
                </div>
                <div class="col-lg-11">
                    <input class="form-control" type="text" name="reason">
                </div>
            </div>
            <div class="row my-2">
                <div class="col-lg-1">
                    <label>Пароль</label>
                </div>
                <div class="col-lg-11">
                    <input class="form-control" type="password" name="password" required>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-lg-1">

                </div>
                <div class="col-lg-2">
                    <button type="submit" class="w-100 btn btn-success">Пополнить</button>
                </div>
            </div>


        </form>
    </div>

    <hr>
    <div class="d-flex align-items-baseline">
        <h4 class="mr-2">Текущий баланс:&nbsp;</h4>
        <b style="font-size: 16px;" class="btn btn-success"> {{ $employee->balance}}</b>
    </div>
    <hr>
    {{-- История начислений (последние 10) --}}
    <h4 class="text-center">История начислений (последние 10 операций)</h4>
    {{-- График Начислений --}}
            <div v-if="payout_graph"  class="card card-p">
                <h3 class="text-center">Тест график</h3>
                <div style="max-width:600px; max-height: 400px; margin: 0 auto;">
                    {!! $chart->container() !!}
                    {!! $chart->script() !!}
                </div>
            </div>
    <div class="card card-p">
        <table class="table">
            <thead>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Тип добавления</th>
            <th>Причина</th>
            </thead>
            <tbody>
            @foreach($balance as $employee_balance_entry)
                <tr>
                    <td>{{ $employee_balance_entry->amount}}</td>
                    <td>{{ $employee_balance_entry->date}}</td>
                    <td>{{ $employee_balance_entry->type}}</td>
                    <td>{{ $employee_balance_entry->reason}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script> -->

@endsection
