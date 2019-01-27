@extends('layouts.limitless')

@section('page_name')
История финансов
@endsection

@section('content')


<div class="form-row">
        <div class="form-group col-md-6">
            <b>Последние штрафы:</b>
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
        <div class="form-group col-md-6">
            <b>Жетоны:</b>
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


@endsection