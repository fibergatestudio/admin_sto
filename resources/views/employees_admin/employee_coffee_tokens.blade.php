@extends('layouts.limitless')

@section('page_name')
    Выдать жетоны на кофе

    {{-- Вернуться : Кнопка --}}
    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>
@endsection

@section('content')
    <div class="card card-p">
        <form action="{{ url('/supervisor/employee_coffee_tokens/add') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

            <input type="number" name="token_count" min="1" max="20" class="py-1 mr-2">

            <button type="submit" class="btn btn-secondary">
                Добавить
            </button>


        </form>
    </div>


    <h2 class="text-center">История выдачи жетонов (последние 10 операций)</h2>
    <div class="card card-p">
        {{-- График Кофе --}}
        <div v-if="payout_graph" style="max-width:600px; margin: 0 auto;">
            Тест график
            <div style="max-width:600px; max-height: 400px;">
                {!! $chart->container() !!}
                {!! $chart->script() !!}
            </div>
        </div>
    </div>
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Количество</th>
            </tr>
            </thead>
            <tbody>
            @foreach($token_logs as $token_log_entry)
                <tr>
                    <td>{{ $token_log_entry->date }}</td>
                    <td>{{ $token_log_entry->token_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    



@endsection