@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <h2>Логи по нарядам</h2>
    <div class="card card-p">
    <table class="table">
        <tr>
            <td><b>Дата</b></td>
            <td><b>Текст лога</b></td>
            <td><b>Подробные данные</b></td>
        </tr>
        @foreach($assignments_logs as $assignment_log)
            <tr>
                {{-- Дата --}}
                <td>
                    <p>
                        {{ date('j. m. Y H:i', strtotime($assignment_log->created_at)) }}
                    </p>

                </td>

                {{-- Текст лога --}}
                <td>
                    <a>
                        {{ $assignment_log->text }}
                    </a>
                </td>

                {{-- Подробные данные --}}
                <td>
                    <a>
                        {{ $assignment_log->description }}
                    </a>
                </td>

            </tr>
        @endforeach
    </table>
</div>

{{ $assignments_logs->links() }}  

@endsection
