@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Рабочие зоны</h2>
    @foreach($workzones as $workzone)
        {{ $workzone->general_name }}
        <br>

    @endforeach
    <hr>
    {{-- Добавить пост: переход --}}
    <a href="{{ url('admin/workzones/add') }}">
        <div class="btn btn-primary">
            Добавить рабочий пост
        </div>
    </a>
@endsection