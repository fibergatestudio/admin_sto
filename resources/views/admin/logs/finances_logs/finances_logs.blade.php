@extends('layouts.limitless')

@section('page_name')
    <!-- Финансы -->
@endsection

@section('content')
    <h2>Логи по финансам</h2>
    <div class="card card-p">
        <table class="table">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Автор</b></td>
                <td><b>Объект</b></td>
                <td><b>Имя</b></td>
                <td><b>Описание</b></td>
                <td><b>Сумма</b></td>
                <td><b>До</b></td>
                <td><b>После</b></td>
            </tr>
            @foreach($employees_finances_logs as $employee_finances_log)
                <tr>
                    {{-- Дата --}}
                    <td>
                        <p>
                            {{ date('j. m. Y H:i', strtotime($employee_finances_log->created_at)) }}
                        </p>

                    </td>

                    {{-- Автор --}}
                    <td>
                        <p>
                            {{ $employee_finances_log->author }}
                        </p>
                    </td>

                    {{-- Объект --}}
                    <td>
                        <p>
                            <a href="/supervisor/employee_finances/{{ $employee_finances_log->employee_id }}">
                                {{ $employee_finances_log->phone}}
                            </a>
                        </p>
                    </td>

                    {{-- Имя --}}
                    <td>
                        <p>
                            {{ $employee_finances_log->employee_name }}
                        </p>
                    </td>

                    {{-- Описание --}}
                    <td>
                        <p>
                            {{ $employee_finances_log->reason }}
                        </p>

                    </td>

                    {{-- Сумма --}}
                    <td>
                        <p>
                            <?php if ($employee_finances_log->action === 'deposit') { ?> 
                                <span style="color: #1be378;">{{ $employee_finances_log->amount }}</span>
                            <?php } else { ?>
                                <?php if (strripos($employee_finances_log->amount, '-') !== FALSE) { ?>
                                    <span style="color: red;">{{ $employee_finances_log->amount }}</span>
                                <?php } else { ?>
                                    <span style="color: red;">-{{ $employee_finances_log->amount }}</span>
                                <?php } ?>                                   
                            <?php } ?>                           
                        </p>
                    </td>

                    {{-- До --}}
                    <td>
                        <p>
                            {{ $employee_finances_log->old_balance }} MDL
                        </p>

                    </td>

                    {{-- После --}}
                    <td>
                        <p>
                            {{ $employee_finances_log->new_balance }} MDL
                        </p>
                    </td>

                </tr>
            @endforeach
        </table>
        <hr>
    </div>

{{ $employees_finances_logs->links() }}    

@endsection