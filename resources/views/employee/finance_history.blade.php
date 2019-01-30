@extends('layouts.limitless')

@section('page_name')
История финансов
@endsection

@section('content')


<div class="form-row">
    <div class="card card-outline-secondary col-md-6">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Последние штрафы:</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Основание</th>
                    </tr>
                </thead>
                @foreach($employee_fines as $employee_fines)
                <tr>
                    <td>
                        {{ $employee_fines->date }}
                    </td>
                    <td>
                        -{{ $employee_fines->amount }}
                    </td>
                    <td>
                        {{ $employee_fines->status }}
                    </td>
                    <td>
                        {{ $employee_fines->reason }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="card card-outline-secondary col-md-6">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Жетоны:</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Кол-во жетонов</th>
                        <th>Сумма</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                @foreach($token_logs as  $token_logs)
                <tr>
                    <td>
                    {{ $token_logs->token_count}}
                    </td>
                    <td>
                    -{{ $token_logs->token_count*5}}
                    </td>
                    <td>
                    {{ $token_logs->date }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        </div>
    </div>
<div class="form-row">
    <div class="card card-outline-secondary col-md-6">
        <div class="form-group">
            <div class="card-header">
                <h3 class="mb-0">Выплаты:</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Основание</th>
                    </tr>
                </thead>
                <tr>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


@endsection