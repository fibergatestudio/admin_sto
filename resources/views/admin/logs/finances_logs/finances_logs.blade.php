@extends('layouts.limitless')

@section('page_name')
    Логи по сотрудникам
@endsection

@section('content')
    <h2>Логи по сотрудникам</h2>
    
    <table class="table">
        <tr>
            <td><b>Дата</b></td>
            <td><b>Текст лога</b></td>
        </tr>
        @foreach($finances_logs as $finances_log)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. n. Y H:i', strtotime($finances_log->created_at)) }}
                    </p>
                
                </td>
                
                {{-- Текст лога --}}
                <td>
                    <a>
                        {{ $finances_log->text }}
                    </a>    
                </td>
                
            </tr>
        @endforeach
    </table>
    <hr>
    
@endsection