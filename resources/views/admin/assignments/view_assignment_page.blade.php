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
          <button type="button" class="btn btn-primary">Изменить</button>
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
  <form action="{{ url('/admin/assignments/change_name') }}" method="POST">
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
          
          {{-- Новое название --}}
          <div class="form-group">
            <label>Тест</label>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="button" class="btn btn-primary">Принять</button>
        </div>
      </div>
    </div>
  </form>
</div>

@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    Клиент: {{ $assignment->client_name }} <br>
    Авто: {{ $assignment->car_name }}<br>
    <hr>

    {{-- Зональные наряды --}}
    <h3>Текущие зональные наряды:</h3>
    <table id="table" class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Название</th>
          <th>Рабочая зона</th>
          <th>Ответственный сотрудник </th>
          <th></th>{{-- Кнопка просмотр --}}
        </tr>
      </thead>
      <tbody id="tablecontents">
        @foreach($sub_assignments as $sub_assignment)
          <tr class="row1" data-id="{{ $sub_assignment->id }}">
            <td>
                <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                <i class="fa fa-ellipsis-v"></i>
                <i class="fa fa-ellipsis-v"></i>
                </div>
            </td>
            <td>{{ $sub_assignment->name }} {{-- Название наряда --}}</td>
            <td>{{ $sub_assignment->workzone_name }} {{-- Название рабочей зоны --}}</td>
            <td>{{ $sub_assignment->responsible_employee }} {{-- Название ответственного сотрудника --}}</td>

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
   $("#table").DataTable();

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