@extends('layouts.limitless')

@section('page_name')
Счета

<div style="margin-top: 10px">
  <!-- Вызов попапа Добавить счет -->
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAccount" style="margin: 10px">
      Добавить счет
  </button>
  <!-- Вызов попапа Добавить(Изменить) категорию -->
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCategory" style="margin: 10px">
      Добавить(Изменить) категорию
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

@endsection


@section('content')

<style type="text/css">
.content { padding: 0; }
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
    width: 20.11%;
}
.tabs-menu>input:checked + label {
    color: #000000;
    border: 1px solid #C0C0C0;
    border-bottom: 1px solid #f2f2f2;
    background: #f2f2f2;
}
#tab_1:checked ~ #txt_1,
#tab_2:checked ~ #txt_2,
#tab_3:checked ~ #txt_3,
#tab_4:checked ~ #txt_4,
#tab_5:checked ~ #txt_5,
#tab_6:checked ~ #txt_6,
#tab_7:checked ~ #txt_7, 
#tab_8:checked ~ #txt_8,
#tab_9:checked ~ #txt_9,
#tab_10:checked ~ #txt_10{ display: block; }
</style>

    <!-- Вкладки -->
<div class="tabs-menu">
  

  <input type="radio" name="inset" value="" id="tab_1" checked>
  <label for="tab_1">Сервис</label>

  <input type="radio" name="inset" value="" id="tab_2">
  <label for="tab_2">Мойка</label>

  <input type="radio" name="inset" value="" id="tab_3">
  <label for="tab_3">Банк MD</label>

  <input type="radio" name="inset" value="" id="tab_4">
  <label for="tab_4">Польша MD</label>

  <input type="radio" name="inset" value="" id="tab_5">
  <label for="tab_5">Фирмы Кредиторы</label>

  <input type="radio" name="inset" value="" id="tab_6">
  <label for="tab_6">Фирмы Дебюторы</label>

  <input type="radio" name="inset" value="" id="tab_7">
  <label for="tab_7">Кредит</label>

  <input type="radio" name="inset" value="" id="tab_8">
  <label for="tab_8">Аукционы</label>

  <input type="radio" name="inset" value="" id="tab_9">
  <label for="tab_9">Инвестиции</label>

  <input type="radio" name="inset" value="" id="tab_10">
  <label for="tab_10">Без категории</label>

  <div id="txt_1">
    <table class="table">
        <thead>
            <tr>
              <th scope="col">№</th>
              <th scope="col">Название</th>
              <th scope="col">Баланс</th>
              <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($accounts as $account)
        @if($account->status === 'active' && $account->category_id == 1)
            <tr>
                {{-- Номер счета --}}
                <td>{{ $account->id }}</td>

                {{-- Название --}}
                <td>{{ $account->name }}</td>

                {{-- Баланс --}}
                <td>{{ $account->balance }}</td>

                {{-- Кнопка подробнее --}}
                <td>
                    <a href="{{ url('/admin/accounts/view/'.$account->id) }}">
                        <div class="btn btn-secondary">
                            Подробнее
                        </div>
                    </a>
                </td>
            </tr>
        @endif
        @endforeach
        </tbody>
    </table>
  </div>
  <div id="txt_2">
  Мойка
  </div>
  <div id="txt_3">
  Банк MD
  </div>
  <div id="txt_4">
  Польша MD
  </div>
  <div id="txt_5">
  Фирмы Кредиторы
  </div>
  <div id="txt_6">
  Фирмы Дебюторы
  </div>
  <div id="txt_7">
  Кредит
  </div>
  <div id="txt_8">
  Аукционы
  </div>
  <div id="txt_9">
  Инвестиции
  </div>
  <div id="txt_10">
  Без категории
  </div>


</div>
@endsection