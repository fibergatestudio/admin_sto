@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <div class="card card-p">
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
    </div>

    <hr>
@endsection