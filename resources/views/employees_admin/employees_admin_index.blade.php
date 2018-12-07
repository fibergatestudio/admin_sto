@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Сотрудники</h2>
    <table class="table">
        @foreach($employee_data as $employee)
            <tr>
                <td>
                    {{ $employee->general_name }}
                </td>

                <td>
                    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection