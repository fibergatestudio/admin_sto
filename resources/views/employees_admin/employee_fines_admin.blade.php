{{-- Страница ШТРАФОВ сотрудника (текущие) --}}

@extends('layouts.basic_bootstrap_layout')

@section('content')
    <h2>Штрафы сотрудника: {{ $employee->general_name }}</h2>
    
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
                    <a href="{{ url('/supervisor/employee_fines/apply_fine/'.$fine->id) }}">
                        <div class="btn btn-primary" style="margin-top: 10px">
                            Отменить
                        </div>
                    </a>

                </td>
            </tr>
        @endforeach
    </table>

    {{-- !!! СДЕЛАТЬ МОДАЛЬНОЕ ОКНО ДОБАВЛЕНИЕ ШТРАФОВ ВРУЧНУЮ --}}
    <div class="btn btn-primary">Добавить штраф вручную</div>
    <div class="btn btn-secondary">История штрафов</div>


    {{-- Добавление штрафа вручную - модальное окно --}}
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>{{-- / MODAL HEADER --}}
                <div class="modal-body">
                    {{-- Непосредственно форма --}}
                        <form>
                            @csrf
                            
                        </form>
                    {{-- Конец формы --}}
                </div>{{-- / MODAL BODY --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>{{-- / MODAL FOOTER --}}
            </div>{{-- / MODAL CONTENT --}}
        </div>{{-- / MODAL DIALOG --}}
    </div>

@endsection