@extends('layouts.limitless')

@section('page_name')
Наряд: {{ $assignment->description }} 

<!-- Button trigger modal -->
<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal1" style="margin-left: 10px">
  Изменить название
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form action="{{ url('/admin/assignments/change_name') }}" method="POST">
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Смена названия наряда</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          {{-- ID наряда --}}
          <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
          
          {{-- Новое название --}}
          <div class="form-group">
            <label>Новое название:</label>
            <input type="text" name="new_name" class="form-control" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary">Добавить</button>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Вызов попапа принятия аванса --> <!-- Тест -->
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#prepaidModal1" style="margin-left: 10px">
    Принять аванс
</button>

<!-- Модальное окно принятия аванса -->
<div class="modal fade" id="prepaidModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form action="{{ url('/admin/manage_assignment/add_income_entry') }}" method="POST">
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="prepaidModalLabel">Форма принятия аванса</h5>
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
            <label>Сумма Аванса</label>
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
            <input type="hidden" name="description" class="form-control" value="Принятие Аванса">
        </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary">Принять</button>
        </div>
      </div>
    </div>
  </form>
</div>
<a href="{{ url('/admin/assignments/assignments_index') }}">
        <div class="btn btn-danger">
            Вернуться
        </div>
    </a>

@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    <div class="row">
      <div class="list-group col-md-6">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Клиент:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $assignment->client_name }}</h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Машина:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $assignment->car_name }}</h4>
        </a>
      </div>
      <div class="list-group col-md-6">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Год машины:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $assignment->car_year }}</h4>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Гос. номер:</h5>
          </div>
          <h4 class="mb-1 text-success">{{ $assignment->car_reg_number }}</h4>
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
            <td>{{ $sub_assignment->responsible_employee }} </td>{{-- Название ответственного сотрудника --}}
            <td>{{ $sub_assignment->date_of_creation }} </td>{{-- Дата создания --}}
            <td>{{ $sub_assignment->start_time }} </td>{{-- Время начала работ --}}
            <td>{{ $sub_assignment->end_time }} </td>{{-- Время окончания работ --}}

            {{-- url('admin/assignments/view/'.$assignment->id.'/management') --}}
            <td>
              {{-- Кнопка управления --}}
              <a href="{{ url('admin/assignments/view/'.$sub_assignment->id.'/management') }}">
                <div class="btn btn-light">
                  Управление зональным нарядом
                </div>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    
    {{-- Добавить новый зональный наряд : Переход --}}
    <a href="{{ url('admin/assignments/add_sub_assignment/'.$assignment->id) }}">
        <div class="btn btn-light">
            Новый зональный наряд
        </div>
    </a>
    <button class="btn btn-default" onclick="window.location.reload()"><b>Обновить страницу (Для теста)</b></button>
    

    {{-- История --}}
    {{-- ... --}}

    {{-- Финансы --}}
    {{-- ... --}}

    <hr>
    {{-- Фотографии : вывод --}}
    <h3>Фотографии:</h3>
    <div class="row">

        <div class="col-sm-4">
            
            {{-- Цикл вывода фотографий --}}
            <p>Принятая машина:</p>
            @foreach($accepted_image_urls as $image_url)
                <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                    <a href="{{ Storage::url($image_url) }}" target="_blank">
                        <img class="img-thumbnail"
                            src="{{ Storage::url($image_url) }}"
                            alt="Another alt text">
                    </a>
                </div>
            @endforeach
            
        </div>{{-- /row --}}

        <div class="col-sm-4">
            
            {{-- Цикл вывода фотографий --}}
            <p>Процесс ремонта:</p>
            @foreach($repair_image_urls as $image_url)
                <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                    <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                    data-image="{{ Storage::url($image_url) }}"
                    data-target="#image-gallery">
                        <img class="img-thumbnail"
                            src="{{ Storage::url($image_url) }}"
                            alt="Another alt text">
                    </a>
                </div>
            @endforeach
            
        </div>{{-- /row --}}

        <div class="col-sm-4">
            
        {{-- Цикл вывода фотографий --}}
        <p>Выдача готовой:</p>
        @foreach($finished_image_urls as $image_url)
            <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                data-image="{{ Storage::url($image_url) }}"
                data-target="#image-gallery">
                    <img class="img-thumbnail"
                        src="{{ Storage::url($image_url) }}"
                        alt="Another alt text">
                </a>
            </div>
        @endforeach
            
        </div>{{-- /row --}}

        {{-- Модальное окно для вывода лайтбокса --}}
        <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="image-gallery-title"></h4>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img id="image-gallery-image" class="img-responsive" src="">
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light float-left" id="show-previous-image"><=
                        </button>

                        <button type="button" id="show-next-image" class="btn btn-light float-right">=>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Конец модального окна для вывода лайтбокса --}}
	</div>
    {{-- Конец вывод фотографий --}}

    {{-- Фотографии : переход на страницу загрузки --}}
    <a href="{{ url('/admin/assignments/'.$assignment->id.'/add_photo_page') }}">
        <div class="btn btn-light">
            К загрузке фотографий
        </div>
    </a>

    {{-- Удаление фотографий : переход на страницу --}}
    <a href="{{ url('/admin/assignments/'.$assignment->id.'/delete_photos_page') }}">
        <div class="btn btn-dark">
            К удалению фотографий
        </div>
    </a><br>
    <br>

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
                <th>Номер наряда</th>
                <!-- <th>Машина</th>
                <th>Год</th>
                <th>Гос.Номер</th>
                <th>Фамилия Клиента</th> -->
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
            <td>
            {{ $income_entry->assignment_id }}<br>
            </td>
            <!-- <td>
            {{ $income_entry->assignment_car_name }}<br>
            </td>
            <td>
            {{ $income_entry->assignment_release_year}}<br>
            </td>
            <td>
            {{ $income_entry->assignment_reg_number }}<br>
            </td>
            <td>
            {{ $income_entry->assignment_client_name }}<br>
            </td> -->
        </tr>
        @endforeach
        {{-- Вывода зональных заходов --}}
        @foreach($zonal_assignment_income as $zonal_income)

        <tr>

          <td>
          {{ $zonal_income->zonal_amount }}<br>
          </td>
          <td>
          {{ $zonal_income->zonal_currency }}<br>
          </td>
          <td>
          {{ $zonal_income->zonal_basis }}<br>
          </td>
          <td>
          {{ $zonal_income->zonal_description }}<br>
          </td>
          <td>
          {{ $zonal_income->sub_assignment_id }} (Зон.)<br>
          </td>
          <td>
          {{ $zonal_income->assignment_id}}<br>
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
    <form action="{{ url('/admin/manage_assignment/add_income_entry') }}" method="POST">
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
    <!--<p>Сумма расходов: {{ $assignment_expense->sum('amount') }}<br></p>-->

    {{-- Добавить расход денег : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExpenseModal">
        Добавить расход денег
    </button>

    {{-- Добавить расход денег : Форма и модальное окно --}}
    <form action="{{ url('/admin/manage_assignment/add_expense_entry') }}" method="POST">
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
    <form action="{{ url('/admin/manage_assignment/add_works_entry') }}" method="POST">
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

    {{-- Расчет рентабельности --}}
    <h2>Расчет рентабельности</h2>
    
    {{-- Вывод списока расходов и доходов --}}
    <table class="table">
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Основание</th>
                <th>Валюта</th>
            </tr>
        </thead>
        <tbody>
        <?php $sum = 0;?>
        @foreach($assignment_income as $income_entry)
        <?php
        if ($income_entry->currency === 'USD') {
            $sum += round(($income_entry->amount)/$usd);
        }
        else if ($income_entry->currency === 'EUR') {
            $sum += round(($income_entry->amount)/$eur);
        }
        else {
            $sum += $income_entry->amount;
        } 
        ?>
        <tr>
            <td>
            {{ $income_entry->amount }}<br>
            </td>
            <td>
            {{ $income_entry->basis }}<br>
            </td>
            <td>
            {{ $income_entry->currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($zonal_assignment_income as $income_entry)
        <?php
        if ($income_entry->zonal_currency === 'USD') {
            $sum += round(($income_entry->zonal_amount)/$usd);
        }
        else if ($income_entry->zonal_currency === 'EUR') {
            $sum += round(($income_entry->zonal_amount)/$eur);
        } 
        else {
            $sum += $income_entry->zonal_amount;
        } 
        ?>
        <tr>
            <td>
            {{ $income_entry->zonal_amount }}<br>
            </td>
            <td>
            {{ $income_entry->zonal_basis }}<br>
            </td>
            <td>
            {{ $income_entry->zonal_currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($assignment_expense as $expense_entry)
        <?php
        if ($expense_entry->currency === 'USD') {
            $sum -= round(($expense_entry->amount)/$usd);
        }
        else if ($expense_entry->currency === 'EUR') {
            $sum -= round(($expense_entry->amount)/$eur);
        } 
        else {
            $sum -= $expense_entry->amount;
        } 
        ?>
        <tr>
            <td>- 
            {{ $expense_entry->amount }}<br>
            </td>
            <td>
            {{ $expense_entry->basis }}<br>
            </td>
            <td>
            {{ $expense_entry->currency }}<br>
            </td>
        </tr>
        @endforeach
        @foreach($zonal_assignment_expense as $expense_entry)
        <?php
        if ($expense_entry->zonal_currency === 'USD') {
            $sum -= round(($expense_entry->zonal_amount)/$usd);
        }
        else if ($expense_entry->zonal_currency === 'EUR') {
            $sum -= round(($expense_entry->zonal_amount)/$eur);
        }  
        else {
            $sum -= $expense_entry->zonal_amount;
        } 
        ?>
        <tr>
            <td>- 
            {{ $expense_entry->zonal_amount }}<br>
            </td>
            <td>
            {{ $expense_entry->zonal_basis }}<br>
            </td>
            <td>
            {{ $expense_entry->zonal_currency }}<br>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <h4 id="total">Итого: {{ $sum }} лей</h4>

@endsection


@section('custom_scripts')
<style>
.btn:focus, .btn:active, button:focus, button:active {
  outline: none !important;
  box-shadow: none !important;
}

#image-gallery .modal-footer{
  display: block;
}

.thumb{
  margin-top: 15px;
  margin-bottom: 15px;
}
</style>

<!-- Скрипты для таблиц -->
 <script type="text/javascript">
 $(function () {
   $("#table").DataTable({
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      }
   });

   $( "#tablecontents" ).sortable({
     items: "tr",
     cursor: 'move',
     opacity: 0.6,
     update: function() {
         sendOrderToServer();
     }
   });

   function sendOrderToServer() {

     var order = [];
     $('tr.row1').each(function(index,element) {
       order.push({
         id: $(this).attr('data-id'),
         position: index+1
       });
     });

     $.ajax({
       type: "POST", 
       dataType: "json", 
       url: "{{ url('/admin/assignments/view/'.$assignment->id) }}",
       data: {
         order:order,
         _token: '{{csrf_token()}}'
       },
       success: function(response) {
           if (response.status == "success") {
             console.log(response);
           } else {
             console.log(response);
           }
       }
     });

   }
 });

</script>

<script>

let modalId = $('#image-gallery');

$(document)
  .ready(function () {

    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#show-previous-image, #show-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#show-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#show-previous-image')
          .hide();
      }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#show-next-image, #show-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
          .text($sel.data('title'));
        $('#image-gallery-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }
  });

// build key actions
$(document)
  .keydown(function (e) {
    switch (e.which) {
      case 37: // left
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
          $('#show-previous-image')
            .click();
        }
        break;

      case 39: // right
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
          $('#show-next-image')
            .click();
        }
        break;

      default:
        return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
  });
</script>

@endsection