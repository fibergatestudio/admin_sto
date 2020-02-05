@extends('layouts.limitless')

@section('page_name')
<div class="col-md-12 d-flex align-items-center">
        <div class="col-md-3">
            <!-- <span>Сотрудники</span> -->
        </div>
        <div class="col-md-5">
            <a href="{{ url('/supervisor/add_employee') }}" class="btn btn-primary">Добавить сотрудника</a>    
        </div>
        <div class="col-md-4">
            <a href="{{ url('/supervisor/employee_archive') }}" class="btn btn-secondary">Архив сотрудников</a>
        </div>  
        <div class="col-md-4">
            <a href="{{ url('/supervisor/employee_migrate') }}" class="btn btn-success">Миграция Сотрудников</a> 
        </div>
</div>
@endsection

@section('content')
<div id="employee">
    <div v-if="wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="w-50 btn btn-success">Мойка</div>
    </div>
    <div v-if="!wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="w-50 btn btn-success">Сервис</div>
    </div>
    <table v-if="wash" style="display: inline-table;" class="table mt-3 card card-p">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th>Баланс</th>
                    <th></th>{{-- Кнопки управления --}}
                </tr>
            </thead>
            <tbody>
            @foreach($employee_data as $employee)
                <tr>
                    <td>
                        <!-- <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a> -->
                        <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">{{ $employee->general_name }}</a>
                    </td>

                    <td>Должность</td>

                    <td>
                        {{ $employee->balance }} MDL
                    </td>

                    <td>
                        <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                            <div class="btn btn-primary">
                                Финансы сотрудника
                            </div>
                        </a>
                    </td>

                    <!-- <td>
                        <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                            <div class="btn btn-success">
                                Редактировать сотрудника
                            </div>
                        </a>
                    </td> -->
                </tr>
            @endforeach
            </tbody>
    </table>
    <table v-if="!wash" class="table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Должность</th>
                <th>Баланс</th>
                <th></th>{{-- Кнопки управления --}}
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
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
        </tbody>
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
