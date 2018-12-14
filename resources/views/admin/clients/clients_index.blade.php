@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Список клиентов</h2>
    @foreach($clients as $client)
        <a href="{{ url('admin/view_client/'.$client->id) }}">
            {{ $client->general_name }}
        </a>
        <br>
    @endforeach
    <hr>
    <a href="{{ url('admin/add_client') }}">
        <div class="btn btn-primary">
            Добавить клиента
        </div>
    </a>
@endsection