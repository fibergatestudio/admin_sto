@extends('layouts.limitless')

@section('page_name')

@endsection

@section('content')
    <h2>Отлично! Вы добавили клиента {{ $client->general_name }}</h2>
    <p>Теперь вы можете <a href="#">добавить машину клиента.</a></p>
@endsection