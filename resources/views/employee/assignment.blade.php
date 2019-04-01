@extends('layouts.limitless')

@section('page_name')
    Наряд # {{ $assignment->id }}<br>
    {{ $assignment->description }}
    <hr>
    <a href="{{ url('/employee/assignments/my_assignments') }}" class="btn btn-danger" title="К активным нарядам">Вернуться</a>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-2">
            <p>Статус: {{ $assignment->status }}</p>
            <p>Утвердил: не утвержден</p>
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
                <button onClick="fnFunction(arg)" type="button" class="btn btn-warning">
                    Не выполнить
                </button>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('/employee/manage_assignment/'.$assignment->id.'/assignment_archive') }}">
                <button onClick="fnFunction(arg)" type="button" class="btn btn-primary">
                    В архив (тест)
                </button>
            </a>
        </div>
    </div>
    <hr>
    {{-- Зональные наряды --}}
    <h3>Текущие зональные наряды:</h3>
    <table id="table" class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Номер</th>
          <th>Название</th>
          <th>Рабочая зона</th>
          <th>Ответственный сотрудник </th>
          <th>Дата</th>
          <th>Время начал работ</th>
          <th>Время окончания работ</th>
          <th></th>{{-- Кнопка просмотр --}}
        </tr>
      </thead>
      <tbody id="tablecontents">
        @foreach($sub_assignments as $sub_assignment)
          <tr class="row1" data-id="{{ $sub_assignment->id }}">
            <td>
                <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                <i class="icon-menu-open"></i>
                <i class=""></i>
                </div>
            </td>
            <td>{{ $sub_assignment->id }}</td>
            <td>{{ $sub_assignment->name }} </td>{{-- Название наряда --}}
            <td>{{ $sub_assignment->workzone_name }} </td>{{-- Название рабочей зоны --}}
            <td>{{ $sub_assignment->employee_name }} </td>{{-- Название ответственного сотрудника --}}
            <td>{{ $sub_assignment->date_of_creation }} </td>{{-- Дата создания --}}
            <td>{{ $sub_assignment->start_time }} </td>{{-- Время начала работ --}}
            <td>{{ $sub_assignment->end_time }} </td>{{-- Время окончания работ --}}

            {{-- url('admin/assignments/view/'.$assignment->id.'/management') --}}
            <td>
              {{-- Кнопка управления --}}
              <a href="{{ url('/employee/assignments/manage_assignment/'.$sub_assignment->id.'/management') }}">
                <div class="btn btn-light">
                  Управление зональным нарядом
                </div>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <hr>

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
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addIncomeModal">
        Добавить заход денег
    </button> -->

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
                                        <option value="MDL">MDL</option>
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
                                        <option value="MDL">MDL</option>
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
            <th>Статус</th>
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
        @if ($work_entry->status == 'unconfirmed')

        <td style="width: 100px; white-space: nowrap;" class="bg-warning">
        Не подтвержден<br>
        </td>

        @else

        <td style="width: 100px;" class="bg-success">
        Подтвержден<br>
        </td>

        @endif
        <td>

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
