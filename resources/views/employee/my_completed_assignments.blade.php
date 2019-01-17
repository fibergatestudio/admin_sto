@extends('layouts.limitless')

@section('page_name')
Мои выполненые наряды
@endsection

@section('content')
    <table class="table">
    <thead>
        <tr>
            <th>Описание</th>
            <th>Статус</th>
            <th></th>{{-- Кнопки управления --}}
        </tr>
    </thead>
        @foreach($assignments as $assignment)
            <tr>
                {{-- Описание --}}
                <td>{{ $assignment->description }}</td>

                {{-- Статус--}}
                <td>{{ $assignment->status }}</td>
                
                {{-- Управление : переход --}}
                <td>
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
@endsection