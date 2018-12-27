@extends('layouts.limitless')

@section('page_name')
    Мои смены
@endsection

@section('content')
    <h2>Сегодняшняя смена</h2>
    {{-- // ... Если смена не открыта, то отобразить кнопку "открыть смену" --}}


    {{-- // ... Если смена открыта, отобразить, во сколько она открыта и кнопку "закрыть смену" --}}
    {{-- Открыть смену : кнопка --}}
    <a href="{{ url('/employee/shifts/start') }}">
        <div class="btn btn-primary">
            Открыть смену
        </div>
    </a>

    <hr>
    {{-- // ... Архив смен --}}
    
    
@endsection