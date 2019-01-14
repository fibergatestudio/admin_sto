@extends('layouts.limitless')

@section('page_name')
    Наряд # {{ $assignment->id }}<br>
    {{ $assignment->description }}
@endsection

@section('content')
    {{-- Доходная часть --}}
    <h2>Доходная часть</h2>
    
    {{-- Вывод текущих заходов денег --}}
    @foreach($assignment_income as $income_entry)
        {{ $income_entry->amount }}<br>
        {{ $income_entry->basis }}<br>
        {{ $income_entry->description }}<br>
    @endforeach

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

                            {{-- Сумма --}}
                            <div class="form-group">
                                <label>Сумма</label>
                                <input type="number" name="amount" min="0" class="form-control" required>
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
    @foreach($assignment_expense as $expense_entry)
        {{ $expense_entry->amount }}<br>
        {{ $expense_entry->basis }}<br>
        {{ $expense_entry->description }}<br>
    @endforeach

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

                            {{-- Сумма --}}
                            <div class="form-group">
                                <label>Сумма</label>
                                <input type="number" name="amount" min="0" class="form-control" required>
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
@endsection
