@extends('layouts.limitless')

@section('page_name')
    Логи по финансам
@endsection

@section('content')
    <h2>Логи по финансам</h2>
    <div class="card card-p">
        <table class="table">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Текст лога</b></td>
            </tr>
            @foreach($employees_finances_logs as $employee_finances_log)
                <tr>
                    {{-- Дата --}}
                    <td>
                        <p>
                            {{ date('j. m. Y H:i', strtotime($employee_finances_log->created_at)) }}
                        </p>

                    </td>

                    {{-- Текст лога --}}
                    <td>
                        <a>
                            {{ $employee_finances_log->text }}
                        </a>
                    </td>

                </tr>
            @endforeach
        </table>
        <hr>
    </div>

    
@endsection