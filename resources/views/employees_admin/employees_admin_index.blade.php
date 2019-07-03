@extends('layouts.limitless')

@section('page_name')
<div class="col-md-12 row">
        <div class="col-md-3">
            Сотрудники
        </div>
        <div class="col-md-5">
            <a href="{{ url('/supervisor/add_employee') }}" class="btn btn-primary">Добавить сотрудника</a>    
        </div>
        <div class="col-md-4">
            <a href="{{ url('/supervisor/employee_archive') }}" class="btn btn-secondary">Архив сотрудников</a>
        </div>  
</div>
@endsection

@section('content')
<div id="employee">
    <div v-if="wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="btn btn-success">Мойка</div>
    </div>
    <div v-if="!wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="btn btn-success">Сервис</div>
    </div>
    <table v-if="wash" class="table">
        @foreach($employee_data as $employee)
            <tr>
                <td>
                    <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a>
                </td>

                <td>
                    Баланс: {{ $employee->balance }}
                </td>

                <td>
                    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                        <div class="btn btn-success">
                            Редактировать сотрудника
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <table v-if="!wash" class="table">
        @foreach($employee_wash as $employee)
            <tr>
                <td>
                    <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                        <div class="btn btn-primary">
                            Финансы сотрудника
                        </div>
                    </a>
                </td>

                <td>
                    <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                        <div class="btn btn-success">
                            Редактировать сотрудника
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
</div>

    <script>
            new Vue({
                el: '#employee',
                data: {
                    wash: true
                }
            
            });
        </script>
@endsection
