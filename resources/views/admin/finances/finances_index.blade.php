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
              <select name="currency" class="form-control">
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


<!-- Модальное окно Добавить(Изменить) категорию -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <form action="{{ url('/admin/finances/add_category') }}" method="POST">
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Добавить(Изменить) категорию</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            {{-- Категории --}}
            <label>Категории</label>
            @php
            $i = 1;
            @endphp
            @foreach($categories as $category)
            @if ($i%2 != 0)
            <div class="form-row">
            @endif
              <div class="form-group col-md-6">
                <input type="text" name="category_name[]" value="{{ $category->name }}" class="form-control">
              </div>
            @if ($i%2 == 0)
            </div>
            @endif
            @php
            $i++;
            @endphp
            @endforeach
            <div class="form-row">
              <div class="form-group col-md-12">
                  <input type="text" name="new_category_name" value="" class="form-control" placeholder="Новая категория">
              </div>
            </div>      

        </div><!-- modal-body -->
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
#tab_1:checked ~ #txt_1{ display: block; }

.total-sum{
  position: absolute;
  right: 150px;
}
</style>

    <!-- Вкладки -->
<div class="tabs-menu">
  @php
  $i = 1;
  @endphp
  @foreach($categories as $category)
  <input type="radio" name="inset" value="" id="tab_<?=$i?>" <?=($i==1)?'checked':''?>>
  <label for="tab_<?=$i?>">{{ $category->name }}</label>
  @php
  $i++;
  @endphp
  @endforeach


  @php
  $i = 1;
  @endphp
  @foreach($categories as $category)

    <div id="txt_<?=$i?>">
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
          @if($account->status === 'active' && $account->category_id == $category->id)
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

  @php
  $i++;
  @endphp
  @endforeach  

</div>

<div class="total-sum">
  <h4>Всего: лей</h4>
  <h4>Оборот: лей</h4>
</div>

@endsection


@section('custom_scripts')
<script type="text/javascript">
  $('.tabs-menu label').click(function () {
    let el = $(this).attr('for').substring(4);
    $('.tabs-menu > div').css('display', 'none');
    $('#txt_'+ el).css('display', 'block');
  });
</script>
@endsection