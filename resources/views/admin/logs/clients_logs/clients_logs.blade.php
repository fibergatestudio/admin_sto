@extends('layouts.limitless')

@section('page_name')
    Логи по клиентам
@endsection

@section('content')
    <h2>Логи по клиентам</h2>
    
    <table class="table">
        <tr>
            <td><b>Дата</b></td>
            <td><b>Текст лога</b></td>
        </tr>
        @foreach($clients_logs as $client_log)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. n. Y H:i', strtotime($client_log->created_at)) }}
                    </p>
                
                </td>
                
                {{-- Текст лога --}}
                <td>
                    <a>
                        {{ $client_log->text }}
                    </a>    
                </td>
                
            </tr>
        @endforeach
    </table>

    <h2>Логи по заметкам сотрудников</h2>
        <table class="table">
        <tr>
            <td><b>Дата</b></td>
            <td><b>Текст лога</b></td>
        </tr>
        @foreach($clients_notes_logs as $client_note_log)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. n. Y H:i', strtotime($client_note_log->created_at)) }}
                    </p>
                
                </td>
                
                {{-- Текст лога --}}
                <td>
                    <a>
                        {{ $client_note_log->text }}
                    </a>    
                </td>
                
            </tr>
        @endforeach
    </table>
@endsection