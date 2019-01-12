@extends('layouts.limitless')

@section('page_name')
    Сканы паспортов сотрудников
@endsection

@section('content')
    <table class="table">
        @foreach($employee_data as $employee)
            <tr>
                <td>
                    {{ $employee->general_name }}
                </td>

                <td>
                    <img src="{{$employee->link_scan}}" alt="" width="100px" height="100px">
                </td>                
            </tr>
        @endforeach
    </table>    
@endsection