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

<?php $sum_tot = 0 ?>
@foreach($employee_data as $employee)
        @if ($employee->balance > 0)
            <?php $sum_tot += $employee->balance; ?>
        @endif
@endforeach


<div class="text-right">Итого по балансу: <?php echo $sum_tot ?> MDL</div>
<div id="employee">
    <div v-if="wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="w-50 btn btn-success">Мойка</div>
    </div>
    <div v-if="!wash" class="col-md-3">
        <div v-on:click="wash = !wash" class="w-50 btn btn-success">Сервис</div>
    </div>
    <br>
    <table id="table_all" v-if="wash" style="display: inline-table;" class="table mt-3 card card-p">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th class="balance">Баланс</th>
                    <th class="no-sort"></th>
                    <th>Месяц</th>
                    <th>Начислено</th>
                    <th></th>{{-- Кнопки управления --}}
                </tr>
            </thead>
            <tbody>
            @foreach($employee_data as $employee)
                <tr>
                    <td>
                     {{ $employee->id }}
                    </td>
                    <td>
                        <!-- <a href="{{ url('/admin/employee/'.$employee->id) }}">{{ $employee->general_name }}</a> -->
                        <a href="{{ url('/supervisor/manage_employee_status/'.$employee->id.'/employee_edit') }}">
                            <div style="font-weight: 600; width: 300px;" class="btn btn-primary">
                                {{ $employee->general_name }}
                            </div>
                        </a>
                    </td>

                    <td>Должность</td>

                    <td>
                    
                    {{ $employee->balance }}
                    </td>
                    <td style="width: 10px;">MDL</td>
                    <td>
                        <?php $sum_last_mnth = 0 ?>
                        @foreach($employee_month_total as $last_month)
                            @if($last_month->employee_id == $employee->id)
                                <?php $sum_last_mnth += $last_month->amount; ?>
                            @endif
                        @endforeach
                        <?php echo $sum_last_mnth; ?> MDL
                    </td>

                    
                    <td>
                        <?php $sum_tot_bal = 0 ?>
                        @foreach($employee_totalbal as $totbal)
                            @if($totbal->employee_id == $employee->id)
                                <?php $sum_tot_bal += $totbal->amount; ?>
                            @endif
                        @endforeach
                        <?php echo $sum_tot_bal ?> MDL
                    </td>

                    <td>
                        <a href="{{ url('/supervisor/employee_finances/'.$employee->id) }}">
                            <div class="btn btn-success">
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
<script type="text/javascript">
 $(function () {
    $("#table_all").DataTable({
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      },
      "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ]
   });
   $("#table").DataTable({
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      },
      "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ]
   });

 });

</script>
<script>
        new Vue({
            el: '#employee',
            data: {
                wash: true
            }
        
        });
</script>
@endsection
