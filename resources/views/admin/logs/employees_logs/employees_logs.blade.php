@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <h2>Логи по сотрудникам</h2>
    <div class="card card-p">
    <table class="table">
        <tr>
            <td><b>Дата</b></td>
            <td><b>Текст лога</b></td>
        </tr>
        @foreach($employees_logs as $employee_log)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. m. Y H:i', strtotime($employee_log->created_at)) }}
                    </p>

                </td>

                {{-- Текст лога --}}
                <td>
                    <a>
                        {{ $employee_log->text }}
                    </a>
                </td>

            </tr>
        @endforeach
    </table>
</div>


    <h2>Логи по заметкам сотрудников</h2>
    <div class="card card-p">
        <table class="table">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Текст лога</b></td>
            </tr>
            @foreach($employees_notes_logs as $employee_note_log)
                <tr>
                    {{-- Дата --}}
                    <td>
                        <p>
                            {{ date('j. m. Y H:i', strtotime($employee_note_log->created_at)) }}
                        </p>

                    </td>

                    {{-- Текст лога --}}
                    <td>
                        <p>
                            {{ $employee_note_log->text }}
                        </p>
                    </td>

                </tr>
            @endforeach
        </table>
    </div>
@endsection
