@extends('layouts.limitless')

@section('page_name')
    Логи по машинам в сервисе
@endsection

@section('content')
    <h2>Логи по машинам в сервисе</h2>
    <div class="card card-p">
        <table class="table">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Текст лога</b></td>
            </tr>
            @foreach($cars_in_service_logs as $cars_in_service_log)
                <tr>
                    {{-- Дата --}}
                    <td>
                        <p>
                            {{ date('j. m. Y H:i', strtotime($cars_in_service_log->created_at)) }}
                        </p>

                    </td>

                    {{-- Текст лога --}}
                    <td>
                        <a>
                            {{ $cars_in_service_log->text }}
                        </a>
                    </td>

                </tr>
            @endforeach
        </table>
        <hr>
    </div>

    <h2>Логи по заметкам сотрудников</h2>
    <div class="card card-p"><table class="table">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Текст лога</b></td>
            </tr>
            @foreach($cars_in_service_notes_logs as $cars_in_service_note_log)
                <tr>
                    {{-- Дата --}}
                    <td>
                        <p>
                            {{ date('j. m. Y H:i', strtotime($cars_in_service_note_log->created_at)) }}
                        </p>

                    </td>

                    {{-- Текст лога --}}
                    <td>
                        <a>
                            {{ $cars_in_service_note_log->text }}
                        </a>
                    </td>

                </tr>
            @endforeach
        </table>
        <hr>
    </div>


@endsection
