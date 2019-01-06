@extends('layouts.limitless')

@section('page_name')
    Мои смены
@endsection

@section('content')
    <h2>Страниц смен</h2>
    

    
    @if($today_shift->count() == 0)
        {{-- Если смена не открыта, то отобразить кнопку "открыть смену" --}}
        {{-- Открыть смену : кнопка --}}
        <a href="{{ url('/employee/shifts/start') }}">
            <div class="btn btn-primary">
                Открыть смену
            </div>
        </a>
    
    @elseif($today_shift->count() == 1 && $today_shift->first()->status == 'active')
        
        {{-- Если смена открыта, отобразить, во сколько она открыта и кнопку "закрыть смену" --}}
        
        Ваша текущая смена началась в: {{ $today_shift->first()->opened_at }}<br>
        
        {{-- Закрыть смену : форма --}}
        <form action="{{ url('/employee/shifts/end/') }}" method="POST">
            @csrf
            <input type="hidden" name="shift_id" value="{{ $today_shift->first()->id }}">

            <button type="submit" class="btn btn-danger">
                Закрыть смену
            </div>
        </form>
        

    @elseif($today_shift->count() == 1 && $today_shift->first()->status == 'closed') {{-- Если сегодня уже была смена--}}
        Ваша сегодняшняя смена началась в {{ $today_shift->first()->opened_at }} и закончилась в {{ $today_shift->first()->closed_at }}

    @endif

    
    

    <hr>
    {{-- // ... Архив смен --}}
    
    
@endsection