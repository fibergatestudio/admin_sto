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



@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    Клиент: {{-- ... --}} <br>
    Авто: {{-- ... --}}<br>
    <hr>

    {{-- Зональные наряды --}}
    <h3>Текущие зональные наряды:</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Название</th>
          <th>Рабочая зона</th>
          <th>Ответственный сотрудник </th>
          <th></th>{{-- Кнопка просмотр --}}
        </tr>
      </thead>
      <tbody>
        @foreach($sub_assignments as $sub_assignment)
          <tr>
            <td>{{ $sub_assignment->name }} {{-- Название наряда --}}</td>
            <td>{{ $sub_assignment->workzone_name }} {{-- Название рабочей зоны --}}</td>
            <td>{{ $sub_assignment->responsible_employee }} {{-- Название ответственного сотрудника --}}</td>
            <td>
              {{-- Кнопка управления --}}
              <a href="{{ url('') }}">
                <div class="btn btn-light">
                  Управление
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
    

    {{-- История --}}
    {{-- ... --}}

    {{-- Финансы --}}
    {{-- ... --}}

    <hr>
    {{-- Фотографии : вывод --}}
    <h3>Фотографии:</h3>
    <div class="row">
		<div class="row">
            
            {{-- Цикл вывода фотографий --}}
            @foreach($image_urls as $image_url)

                
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
    </a>

  

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