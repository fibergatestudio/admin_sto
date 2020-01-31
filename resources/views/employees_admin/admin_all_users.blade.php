@extends('layouts.limitless')

@section('page_name')
   
@endsection

@section('content')
    <div class="card card-p">
        <table class="table">
            <thead>
            <tr>
                <th>Общее имя</th>
                <th>Роль</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($all_users as $employee)
                <tr>
                    <td>
                        {{ $employee->general_name }}
                    </td>

                    <td>
                        {{ $employee->role }}
                    </td>

                    <td>
                        <a href="{{ url('admin/access/change_access_rights/'.$employee->id) }}" class="btn btn-secondary">Изменить права доступа</a>
                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
    </div>

    
    

@endsection