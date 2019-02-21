@extends('layouts.limitless')

@section('page_name')
Изменить права доступа
@endsection

@section('content')

{{-- Форма --}}
<form action="{{ url('admin/change_access_rights_post/') }}" method="POST">
    @csrf
    {{-- ID сотрудника --}}
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    
    <table class="table">
        <thead>
            <tr>
                <th>Общее имя</th>
                <th>Администратор</th>
                <th>Мастер</th>  
                <th>Рабочий</th>
                <th>Снабженец</th>               
            </tr>
        </thead>
        <tbody>        
            <tr>
                <td>
                    {{ $user->general_name }} - {{ $user->role }}
                </td>
                <td>
                    <input type="radio" name="rights" value="admin" @if($user->role == 'admin') checked @endif>
                </td>
                <td>
                    <input type="radio" name="rights" value="master" @if($user->role == 'master') checked @endif>
                </td>
                <td>
                    <input type="radio" name="rights" value="employee" @if($user->role == 'employee') checked @endif>
                </td>  
                <td>
                    <input type="radio" name="rights" value="supply_order" @if($user->role == 'supply_officer') checked @endif>
                </td>           
            </tr>
        </tbody>        
    </table>   

    <div>
        <input type="submit" class="btn btn-success" value="Изменить">
    </div>
    </div>
</form>
{{-- Конец формы --}}

@endsection