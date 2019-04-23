@extends('layouts.limitless')

@section('page_name')
Штрафы сотрудника: {{ $employee->general_name }}
<a href="{{ url('supervisor/employee_finances/'.$employee->id) }}">
    <div class="btn btn-danger">Вернуться</div>
</a>
<div class="btn btn-primary" data-toggle="modal" data-target="#addFineModal">Добавить штраф вручную</div>
<div class="btn btn-secondary" data-toggle="modal" data-target="#fineHistory">История штрафов</div>


@endsection

@section('content')
    <b>Ожидают подтверждения:</b>
    <table class="table">
        @foreach($fines as $fine)
            <tr>
                <td>{{ $fine->amount }}</td>
                <td>{{ $fine->date }}</td>
                <td>{{ $fine->reason }}</td>
                <td>
                    {{-- Применить штраф --}}
                    <a href="{{ url('/supervisor/employee_fines/apply_fine/'.$fine->id) }}">
                        <div class="btn btn-danger">
                            Применить
                        </div>
                    </a>
                    <br>
                    {{-- Отменить штраф --}}
                    <a onclick="return do_check();" href="{{ url('/supervisor/employee_fines/quash_fine/'.$fine->id) }}">
                        <div class="btn btn-primary" style="margin-top: 10px">
                            Отменить
                        </div>
                    </a>

                </td>
            </tr>
        @endforeach
    </table>

    {{-- График Начислений --}}
    <div v-if="payout_graph">
        Тест график
        <div style="max-width:600px; max-height: 400px;">
            {!! $chart->container() !!}
            {!! $chart->script() !!}
        </div>
    </div>

    <div class="btn btn-primary" data-toggle="modal" data-target="#addFineModal">Добавить штраф вручную</div>
    <div class="btn btn-secondary" data-toggle="modal" data-target="#fineHistory">История штрафов</div>

    <a href="{{ url('supervisor/employee_finances/'.$employee->id) }}">
        <div class="btn btn-danger">Вернуться</div>
    </a>


    {{-- Добавление штрафа вручную - модальное окно --}}
    <div class="modal" tabindex="-1" role="dialog" id="addFineModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавление штрафа вручную</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>{{-- / MODAL HEADER --}}
                {{-- Непосредственно форма --}}
                <form action="{{ url('/supervisor/employee_fines/add_fine_manually') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- ID сотрудника --}}
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                        {{-- Размер штрафа --}}
                        <div class="form-group">
                            <label>Размер штрафа</label>
                            <input type="number" class="form-control" name="fine_amount">
                        </div>

                        {{-- Причина штрафа --}}
                        <div class="form-group">
                            <label>Причина наложения штрафа</label>
                            <input type="text" class="form-control" name="fine_reason" placeholder="Причина наложения штрафа">
                        </div>

                        <b>* После добавления штраф попадёт на рассмотрение, где его нужно будет подтвердить</b>

                    </div>{{-- / MODAL BODY --}}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>{{-- / MODAL FOOTER --}}
                </form>
                {{-- Конец формы --}}
            </div>{{-- / MODAL CONTENT --}}
        </div>{{-- / MODAL DIALOG --}}
    </div>

    {{-- История штрафов - модальное окно --}}
    <div class="modal" tabindex="-1" role="dialog" id="fineHistory">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">История штрафов</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>{{-- / MODAL HEADER --}}
                {{-- Непосредственно форма --}}
                    <div class="modal-body">
                        <div class="form-group">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Тип</th>
                                        <th>Сумма</th>
                                        <th>Остаток</th>
                                        <th>Основание</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                @foreach ($fines_history as $fh )
                                <tr>
                                    <td>
                                        {{ $fh->date }}
                                    </td>
                                    <td>
                                        {{ $fh->type }}
                                    </td>
                                    <td>
                                        {{ $fh->amount }}
                                    </td>
                                    <td>
                                        {{ $fh->old_balance }}
                                    </td>
                                    <td>
                                        {{ $fh->reason }}
                                    </td>
                                    <td>
                                        {{ $fh->status }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>{{-- / MODAL BODY --}}
                    <div class="modal-footer">
                    <hr>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>{{-- / MODAL FOOTER --}}
                {{-- Конец формы --}}
            </div>{{-- / MODAL CONTENT --}}
        </div>{{-- / MODAL DIALOG --}}
    </div>

<script>
function do_check()
{
    var return_value=prompt("Введите пароль для отмены Пароль(123)");
    if(return_value==="123")
        return true;
    else
        alert("Неверный пароль!");
        return false;

}
</script>
@endsection
