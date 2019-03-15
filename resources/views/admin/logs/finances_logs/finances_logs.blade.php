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
        @foreach($employee_finances_log_entry as $employee_finances_logs_entry)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. n. Y H:i', strtotime($employee_finances_logs_entry->created_at)) }}
                    </p>
                
                </td>
                
                {{- - Текст лога - -}}
                <td>
                    <a>
                        {{ $employee_finances_log_entry->text }}    
                    </a>    
                </td>
                
            </tr>
        @endforeach
    </table>
    <hr>
    
@endsection