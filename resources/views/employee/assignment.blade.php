@extends('layouts.limitless')

@section('page_name')
    Наряд # {{ $assignment->id }}<br>
    {{ $assignment->description }}

@endsection

@section('content')

<div class="row">
        <div class="col-md-2">
            <p>Статус: {{ $assignment->status }}</p>
        </div>
        <div class="col-md-2">
            <a href="{{ url('/employee/manage_assignment/'.$assignment->id.'/assignment_complete') }}">
                <button type="button" class="btn btn-success">
                    Выполнить
                </button>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('/employee/manage_assignment/'.$assignment->id.'/assignment_uncomplete') }}">
                <button type="button" class="btn btn-warning">
                    Невыполнить
                </button>
            </a>
        </div>
    </div>

    {{-- Доходная часть --}}
    <h2>Доходная часть</h2>
    
    {{-- Вывод текущих заходов денег --}}
    <table class="table">
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Валюта</th>
                <th>Основание</th>
                <th>Описание</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
        @foreach($assignment_income as $income_entry)
        <tr>
            <td>
            {{ $income_entry->amount }}<br>
            </td>
            <td>
            {{ $income_entry->currency }}<br>
            </td>
            <td>
            {{ $income_entry->basis }}<br>
            </td>
            <td>
            {{ $income_entry->description }}<br>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <!--<p>Сумма заходов: {{ $assignment_income->sum('amount') }}<br></p>-->

    {{-- Добавить заход денег : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addIncomeModal">
        Добавить заход денег
    </button>

    {{-- Добавить заход денег : Форма и модальное окно --}}
    <form action="{{ url('/employee/manage_assignment/add_income_entry') }}" method="POST">
        @csrf

        <div class="modal fade" id="addIncomeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить заход денег</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            {{-- ID наряда --}}
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                            <div class="form-row">
                                {{-- Сумма --}}
                                <div class="form-group col-md-6">
                                    <label>Сумма</label>
                                    <input type="number" name="amount" min="0" class="form-control" required>
                                </div>
                                {{-- Валюта --}}
                                <div class="form-group col-md-6">
                                    <label>Валюта</label>
                                    <!--<input type="number" name="amount" min="0" class="form-control" required>-->
                                    <select name="currency"class="form-control">
                                        <option value="UAH">UAH</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Основание --}}
                            <div class="form-group">
                                <label>Основание (реквизиты документа или действие)</label>
                                <input type="text" name="basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>

                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

    <hr>
    {{-- Расходная часть --}}
    <h2>Расходная часть</h2>
    
    {{-- Вывод текущих заходов денег --}}
    <table class="table">
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Валюта</th>
                <th>Основание</th>
                <th>Описание</th>
                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
        @foreach($assignment_expense as $expense_entry)
        <tr>
            <td>
            {{ $expense_entry->amount }}<br>
            </td>
            <td>
            {{ $expense_entry->currency }}<br>
            </td>
            <td>
            {{ $expense_entry->basis }}<br>
            </td>
            <td>
            {{ $expense_entry->description }}<br>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <!--<p>Сумма расходов: {{ $assignment_expense->sum('amount') }}<br></p>-->

    {{-- Добавить расход денег : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExpenseModal">
        Добавить расход денег
    </button>

    {{-- Добавить расход денег : Форма и модальное окно --}}
    <form action="{{ url('/employee/manage_assignment/add_expense_entry') }}" method="POST">
        @csrf

        <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить расход денег</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            {{-- ID наряда --}}
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                            <div class="form-row">
                                {{-- Сумма --}}
                                <div class="form-group col-md-6">
                                    <label>Сумма</label>
                                    <input type="number" name="amount" min="0" class="form-control" required>
                                </div>
                                {{-- Валюта --}}
                                <div class="form-group col-md-6">
                                    <label>Валюта</label>
                                    <!--<input type="number" name="amount" min="0" class="form-control" required>-->
                                    <select name="currency"class="form-control">
                                        <option value="UAH">UAH</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Основание --}}
                            <div class="form-group">
                                <label>Основание (реквизиты документа или действие)</label>
                                <input type="text" name="basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>

                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

    <hr>

    {{-- Список выполненных работ --}}
    <h2>Список выполненных работ</h2>
    
    {{-- Вывод списока выполненных работ --}}
    <table class="table">
    <thead>
        <tr>
            <th>Название</th>
            <th>Основание</th>
            <th>Дата</th>
            <th></th>{{-- Кнопки управления --}}
        </tr>
    </thead>
    <tbody>
    @foreach($assignment_work as $work_entry)
    <tr>
        <td>
        {{ $work_entry->basis }}<br>
        </td>
        <td>
        {{ $work_entry->description }}<br>
        </td>
        <td>
        {{ date('d m Y', $work_entry->created_at->timestamp) }}<br>
        </td>
    </tr>
    @endforeach
    </tbody>
    </table>

    {{-- Добавить список выполненных работ : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWorksModal">
        Добавить список выполненных работ
    </button>

    {{-- Добавить выполненую работа : Форма и модальное окно --}}
    <form action="{{ url('/employee/manage_assignment/add_works_entry') }}" method="POST">
        @csrf

        <div class="modal fade" id="addWorksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить список выполненных работ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            {{-- ID наряда --}}
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                            
                            {{-- Основание --}}
                            <div class="form-group">
                                <label>Основание (реквизиты документа или действие)</label>
                                <input type="text" name="basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>

                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>{{-- /modal-content --}}
            </div>{{-- /modal-dialog --}}
        </div>{{-- /modal fade --}}
    </form>

    <hr>
@endsection
