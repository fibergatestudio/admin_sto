@extends('layouts.limitless')

@section('page_name')
    Документы сотрудника - {{$employee->general_name}}
@endsection

@section('content')
    <div class="row">
        <div class="row">    
            {{-- Цикл вывода документов --}}
            
            @foreach($images as $image)

                
                <div class="col-lg-2 col-md-3 col-xs-6 thumb">
                    <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                    data-image="..{{Storage::url($image)}}"
                    data-target="#image-gallery">
                        <img class="img-thumbnail"
                            src="..{{Storage::url($image)}}"
                            alt="скан паспорта {{$employee->general_name}}">
                    </a>
                </div>
            @endforeach
        </div>    
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
     {{-- Документы : переход на страницу загрузки --}}   
    <a class="btn btn-light" href="{{ url('/add_documents/'.$employee->id) }}">Добавить документ</a>
     
    {{-- Удаление документов : переход на страницу --}}              
    <a class="btn btn-secondary" href="{{ url('/documents_delete/'.$employee->id) }}">Удалить документ</a>

       
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