@extends('layouts.limitless')

@section('page_name')
<div style="margin-top: 10px">
    <a href="{{ url('/admin/finances/index') }}">
        <button type="button" class="btn btn-warning" >
        Назад
        </button>
    </a>
</div>


@endsection


@section('content')

<div class=" custom-card">
    <div class="card">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Название</th>
                <th scope="col">Валюта</th>
                <th scope="col">Баланс</th>
            </tr>
            </thead>
            <tbody>
            @foreach($archive_acc as $arch)
                <tr>
                    <td> {{ $arch->id }} </td>
                    <td> {{ $arch->name }} </td>
                    <td> {{ $arch->currency }} </td>
                    <td> {{ $arch->balance }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@section('custom_scripts')

@endsection