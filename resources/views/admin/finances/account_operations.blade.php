<?php
    function new_date($mysql_date){
        /*$date_dump_full = explode(' ', $mysql_date);
        $date_dump = explode('-', $date_dump_full[0]);
        return $date_dump[2].'/'.$date_dump[1].'/'.$date_dump[0].' '.$date_dump_full[1];*/
        return $mysql_date;
    }
?>

@extends('layouts.limitless')

@section('page_name')
Операции со счетом

<div style="margin-top: 10px">
  <a href="{{ url('/admin/finances/index') }}" class="btn btn-danger" style="margin: 10px">Вернуться</a>
  <!-- Вызов попапа Добавить счет -->
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAccount" style="margin: 10px">
      Добавить счет
  </button>
  <!-- Вызов попапа Изменить счет -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeAccount" style="margin: 10px">
      Изменить счет
  </button>
  <!-- Вызов попапа Добавить операцию -->
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addOperation" style="margin: 10px">
      Добавить операцию
  </button>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchModal" style="margin: 10px">Поиск</button>
  <button type="button" class="btn btn-primary" style="margin: 10px">Архив</button>

</div>

<!-- Модальное окно Добавить счет -->
<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <form action="{{ url('/admin/finances/add_account') }}" method="POST">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Добавить счет</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-row">
                            {{-- Название --}}
                            <div class="form-group col-md-6">
                                <label>Название</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            {{-- Категория --}}
                            <div class="form-group col-md-6">
                                <label>Категория</label>
                                <select name="category" class="form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- Валюта --}}
                            <div class="form-group col-md-6">
                                <label>Валюта</label>
                                <select name="currency"class="form-control">
                                    <option value="MDL">MDL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Аккаунт (необязательно)</label>
                                <select name="user_email" class="form-control">
                                    <option value="" selected >Выбрать</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Создать</button>
                    </div>
                </div>
            </div>
        </form>

</div>

<!-- Модальное окно Добавить операцию -->
<style type="text/css">
.tabs-menu { width: 100%; padding: 0px; margin: 0 auto; }
.tabs-menu>input { display:none; }
.tabs-menu>div {
    display: none;
    padding: 12px;
    border: 1px solid #C0C0C0;
}
.tabs-menu>label {
    display: inline-block;
    padding: 7px;
    margin: 0 -5px -1px 0;
    text-align: center;
    color: #666666;
    border: 1px solid #C0C0C0;
    background: #E0E0E0;
    cursor: pointer;
    width: 33.33%;
}
.tabs-menu>input:checked + label {
    color: #000000;
    border: 1px solid #C0C0C0;
    border-bottom: 1px solid #f2f2f2;
    background: #f2f2f2;
}
#tab_1:checked ~ #txt_1,
#tab_2:checked ~ #txt_2,
#tab_3:checked ~ #txt_3{ display: block; }

.choose-category ul{
  list-style-type: none;
  display: none;
  padding: 0;
  position: absolute;
  z-index: 10;
  width: 96%;
  top: 75;
}
.choose-category p, .choose-category li{
  cursor: pointer;
}
.choose-category li:hover{
  background: #73a9f5;
}

.table-operations{
 font-size: .7625rem;
}
</style>


<div class="modal fade" id="addOperation" tabindex="-1" role="dialog" aria-labelledby="addOperationModalLabel" aria-hidden="true">
  <form action="{{ url('/admin/accounts/add_operation/'.$account_operations[0]->account_id) }}" method="POST">
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addOperationModalLabel">Добавить операцию</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <!-- Вкладки -->
            <div class="tabs-menu">
              @php
              $categories_arr = [];
              @endphp

              <input type="radio" name="inset" value="" id="tab_1" checked>
              <label for="tab_1">Расход</label>

              <input type="radio" name="inset" value="" id="tab_2">
              <label for="tab_2">Доход</label>

              <input type="radio" name="inset" value="" id="tab_3">
              <label for="tab_3">Перевод на счет</label>

              <div class="tabs-menu-child" id="txt_1">

                {{-- Тип операции --}}
                <input type="hidden" name="type_operation" value="Расход">

                <div class="form-row">
                  {{-- Тег --}}
                  <div class="form-group col-md-6">
                      <label>Тег</label>
                      <input type="text" name="tag" class="form-control">
                  </div>
                  {{-- Категория --}}
                  <div class="form-group col-md-6">
                      <label>Категория</label>
                      @if(count($account_operation_categories) > 0)
                      <p onclick="chooseCategory()" class="form-control">Выберите</p>
                      <ul class="listCategories">
                        <li><input type="text" name="category" onblur="createCategory(this)" class="form-control" placeholder="Введите новую категорию" autocomplete="off"></li>
                        @foreach($account_operation_categories as $category)
                        @php
                        $categories_arr[] = $category->name;
                        @endphp
                        <li class="form-control" data-value="{{ $category->name }}">{{ $category->name }}</li>
                        @endforeach
                      </ul>
                      @else
                      <input type="text" name="category" class="form-control" placeholder="Введите новую категорию" required>
                      @endif
                  </div>
                </div>

                <div class="form-row">
                  {{-- Сумма --}}
                  <div class="form-group col-md-6">
                    <label>Сумма</label>
                    <input type="text" name="expense" class="form-control" required>
                  </div>
                  {{-- Дата --}}
                  <div class="form-group col-md-6">
                    <label>Дата</label>
                    <input type="text" name="disabled-date" class="form-control" disabled value="{{ date('Y-m-d H:i:s') }}">
                    <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}">
                  </div>
                </div>

                {{-- Описание --}}
                <div class="col-md-6">
                  <label>Описание</label>
                  <textarea name="comment" class="form-control"></textarea>
                </div>


              </div><!--txt_1 Расход -->


              <div class="tabs-menu-child" id="txt_2">

                {{-- Тип операции --}}
                <input type="hidden" name="type_operation" value="Доход">

                <div class="form-row">
                  {{-- Тег --}}
                  <div class="form-group col-md-6">
                      <label>Тег</label>
                      <input type="text" name="tag" class="form-control">
                  </div>
                  {{-- Категория --}}
                  <div class="form-group col-md-6 choose-category">
                      <label>Категория</label>
                      @if(count($account_operation_categories) > 0)
                      <p onclick="chooseCategory()" class="form-control">Выберите</p>
                      <ul class="listCategories">
                        @php
                        $categories_arr = [];
                        @endphp
                        <li><input type="text" name="category" onblur="createCategory(this)" class="form-control" placeholder="Введите новую категорию" autocomplete="off"></li>
                        @foreach($account_operation_categories as $category)
                        @php
                        $categories_arr[] = $category->name;
                        @endphp
                        <li class="form-control" data-value="{{ $category->name }}">{{ $category->name }}</li>
                        @endforeach
                      </ul>
                      @else
                      <input type="text" name="category" class="form-control" placeholder="Введите новую категорию" required>
                      @endif
                  </div>
                </div>

                <div class="form-row">
                  {{-- Сумма --}}
                  <div class="form-group col-md-6">
                    <label>Сумма</label>
                    <input type="text" name="income" class="form-control" required>
                  </div>
                  {{-- Дата --}}
                  <div class="form-group col-md-6">
                    <label>Дата</label>
                    <input type="text" name="disabled-date" class="form-control" disabled value="{{ date('Y-m-d H:i:s') }}">
                    <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}">
                  </div>
                </div>

                {{-- Описание --}}
                <div class="col-md-6">
                  <label>Описание</label>
                  <textarea name="comment" class="form-control"></textarea>
                </div>

              </div><!--txt_2 Доход -->


              <div class="tabs-menu-child" id="txt_3">

                {{-- Тип операции --}}
                <input type="hidden" name="type_operation" value="Перевод на счет">

                <div class="form-row">
                  {{-- На счет --}}
                  <div class="form-group col-md-6">
                      <label>На счет</label>
                      <select name="to_account_id" class="form-control">
                          @foreach($accounts as $account)
                          @if($account->id != $account_operations[0]->account_id)
                          <option value="{{ $account->id }}">{{ $account->name }}</option>
                          @endif
                          @endforeach
                      </select>
                  </div>
                  {{-- Описание --}}
                  <div class="form-group col-md-6">
                    <label>Описание</label>
                    <textarea name="comment" class="form-control"></textarea>
                  </div>
                </div>

                <div class="form-row">
                  {{-- Сумма --}}
                  <div class="form-group col-md-6">
                    <label>Сумма</label>
                    <input type="text" name="expense" class="form-control" required>
                  </div>
                  {{-- Сумма получения--}}
                  <div class="form-group col-md-6">
                    <label>Сумма получения</label>
                    <input type="text" name="income_to" class="form-control" value="">
                  </div>
                </div>

                {{-- Дата --}}
                <div class="form-group col-md-6">
                  <label>Дата</label>
                  <input type="text" name="disabled-date" class="form-control" disabled value="{{ date('Y-m-d H:i:s') }}">
                  <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}">
                </div>

              </div><!--txt_3 Перевод на счет -->


            </div><!-- tabs-menu -->

        </div><!-- modal-body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="submit" id="createOperation" class="btn btn-primary">Создать</button>
        </div>
      </div>
    </div>
  </form>
</div>

@endsection


@section('content')
<div class="card card-p">
    <table class="table table-operations">
        <thead>
        <tr>
            <th scope="col">Дата</th>
            <th scope="col">Автор</th>
            <th scope="col">Тег/Работник (ФИО работника)</th>
            <th scope="col">Категория</th>
            <th scope="col">Комментарий</th>
            <th scope="col">Расход</th>
            <th scope="col">Доход</th>
            <th scope="col">Баланс</th>
        </tr>
        </thead>
        <tbody>
        @foreach($account_operations as $operation)
            <tr>
                {{-- Дата --}}
                <td>{{ new_date($operation->date) }}</td>

                {{-- Автор --}}
                <td>{{ $operation->author }}</td>

                {{-- Тег/Работник (ФИО работника) --}}
                <td>{{ $operation->tag }}</td>

                {{-- Категория --}}
                <td>{{ $operation->category }}</td>

                {{-- Комментарий --}}
                <td>{{ $operation->comment }}</td>

                {{-- Расход --}}
                <td>{{ $operation->expense }}</td>

                {{-- Доход --}}
                <td>{{ $operation->income }}</td>

                {{-- Баланс --}}
                <td>{{ $operation->balance }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


@endsection

<script type="text/javascript">
    var categoriesArr = '<?=json_encode($categories_arr,JSON_UNESCAPED_UNICODE) ?>';
    categoriesArr = JSON.parse(categoriesArr);
</script>

@section('custom_scripts')

<script type="text/javascript">

  // Корректная передача данных
  $('#createOperation').click(function(event){
    event.preventDefault();
    $(".tabs-menu-child").each(function(k,elem) {
      if ($(elem).css('display') === 'none') {
        $(elem).find('input').prop('disabled',true);
        $(elem).find('select').prop('disabled',true);
        $(elem).find('textarea').prop('disabled',true);
      }
    });

    /*if ($('input[name="type_operation"]').val() === "Перевод на счет") {
      const toAccountId = $('select[name="to_account_id"]').val();
      console.log(toAccountId);
    }*/
    $(this).parent().parent().parent().parent().submit();
  });

  // Вывод категорий операций
  function chooseCategory() {
    $('.listCategories').toggle();
  }

  function createCategory(elem){
    const valText = $(elem).val();
    $('.choose-category p').text(valText);
    $('.listCategories').hide();
  }

  $('.listCategories li.form-control').click(function(){
    const valText = $(this).text();
    $('.choose-category p').text(valText);
    $('input[name="category"]').val(valText);
    $('.listCategories').hide();
  });

  $('.listCategories input').keyup(function(){
    let valText = $(this).val();
    $('.choose-category p').text(valText);
    valText = valText.toLowerCase();
    $('.listCategories li.form-control').each(function(k,elem){
      let string = $(elem).text().toLowerCase();
      if (!(string.indexOf(valText) + 1)) {
        $(elem).hide();
      }
      else{
        $(elem).show();
      }
    });
  });
  // End "Вывод категорий операций"

</script>

@endsection
