@extends('layouts.limitless')
@section('page_name')
    Архив нарядов клиента
@endsection
@section('content')
    @if($empty==0)
        <table class="table">
            <thead>
            <tr>
                <th>Описание</th>
                <th>Ответственный работник</th>
                <th>Дата начала</th>
                <th>Дата завершения</th>
                <th>
                </th>
            </tr>
            </thead>
            @foreach($assignments_archiv as $assignment_archiv)
                @if($assignment_archiv->status == 'archived')
                    <tr>
                        <td>{{($assignment_archiv->description)}} {{-- Описание наряда --}}</td>
                        <td>{{($assignment_archiv->employee_name)}} {{-- Ответственный работник --}}</td>
                        <td>{{($assignment_archiv->date_of_creation)}} {{-- Дата начала --}}</td>
                        <td>{{($assignment_archiv->date_of_completion)}} {{-- Дата завершения --}}</td>
                        <td>
                            <a href="{{ url('/client/sub_assignments/'.$assignment_archiv->id) }}">
                                <div class="btn btn-secondary">
                                    Архив зональных нарядов клиента
                                </div>
                            </a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center">
                <h3>Архивных нарядов данного клиента нет</h3>
            </div>
        </div>
    @endif
@endsection
