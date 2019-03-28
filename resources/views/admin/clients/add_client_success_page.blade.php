@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <h2>Отлично! Вы добавили клиента {{ $client->general_name }}</h2>
    <p>Теперь вы можете <a href="{{ url('admin/cars_in_service/add') }}">добавить машину клиента.</a></p>

    <hr>
	<a href="{{ url('admin/clients/clients_index') }}" class="btn btn-danger">Вернуться</a>
@endsection