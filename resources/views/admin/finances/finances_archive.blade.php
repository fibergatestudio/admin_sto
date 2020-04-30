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
                <th scope="col">Дата</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($all_cats as $cat)
                <tr>
                    <td> {{ $cat->id }} </td>
                    <td> {{ $cat->name }} </td>
                    <td> {{ $cat->updated_at }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@section('custom_scripts')

@endsection