@extends('layouts.limitless')

@section('page_name')
    Зональный наряд: {{ $sub_assignment->name }}
    <hr>
    <a href="{{ url('/master/assignments/view/'.$assignment->id) }}" class="btn btn-danger">Вернуться</a>
@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    Описание наряда: {{ $sub_assignment->description }}<br>
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
        @foreach($zonal_assignment_income as $income_entry)
            <tr>
                <td>
                    {{ $income_entry->zonal_amount }}<br>
                </td>
                <td>
                    {{ $income_entry->zonal_currency }}<br>
                </td>
                <td>
                    {{ $income_entry->zonal_basis }}<br>
                </td>
                <td>
                    {{ $income_entry->zonal_description }}<br>
                </td>
                <td>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#income_entry{{$income_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>

            <form action="{{ url('/master/zonal_income_entry/'.$income_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="income_entry{{$income_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Редактировать доходную часть</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="{{ $income_entry->id }}">

                                {{-- Сумма --}}
                                <div class="form-group">
                                    <label>Сумма</label>
                                    <input type="text" name="new_amount" class="form-control" value="{{ $income_entry->zonal_amount }}" required >
                                </div>

                                {{-- Валюта --}}
                                <div class="form-group">
                                    <label>Валюта</label>
                                    <select name="new_currency" class="form-control">
                                        @foreach (array('MDL','USD','EUR') as $key => $value)
                                            <option value="{{ $value }}"
                                                    @if ($value == $income_entry->zonal_currency)
                                                    selected="selected"
                                                    @endif
                                            >{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Основание --}}
                                <div class="form-group">
                                    <label>Основание</label>
                                    <input type="text" name="new_basis" class="form-control" value="{{ $income_entry->zonal_basis  }}" required>
                                </div>

                                {{-- Описание --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $income_entry->zonal_description  }}" required>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-success">Сохранить</button>
                            </div>
                        </div>{{-- /modal-content --}}
                    </div>
                </div>

            </form>

        @endforeach
        </tbody>
    </table>

    {{-- Добавить заход денег : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addZonalIncomeModal">
        Добавить заход денег
    </button>

    {{-- Добавить зональный заход денег : Форма и модальное окно --}}
    <form action="{{ url('admin/assignments/view/'.$sub_assignment->id.'/management/add_zonal_assignment_income') }}" method="POST">
        @csrf

        <div class="modal fade" id="addZonalIncomeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить зональный заход денег</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            {{-- ID наряда --}}
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                            <input type="hidden" name="assignment_id" value="{{ $sub_assignment->id }}">

                            <div class="form-row">
                                {{-- Сумма --}}
                                <div class="form-group col-md-6">
                                    <label>Сумма</label>
                                    <input type="number" name="zonal_amount" min="0" class="form-control" required>
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
                                <input type="text" name="zonal_basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="zonal_description" class="form-control" required></textarea>
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
        @foreach($zonal_assignment_expense as $expense_entry)
            <tr>
                <td>
                    {{ $expense_entry->zonal_amount }}<br>
                </td>
                <td>
                    {{ $expense_entry->zonal_currency }}<br>
                </td>
                <td>
                    {{ $expense_entry->zonal_basis }}<br>
                </td>
                <td>
                    {{ $expense_entry->zonal_description }}<br>
                </td>

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#expense_entry{{$expense_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>

            <form action="{{ url('/master/zonal_expense_entry/'.$expense_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="expense_entry{{$expense_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Редактировать расходную часть</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="{{ $expense_entry->id }}">

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Сумма</label>
                                    <input type="text" name="new_amount" class="form-control" value="{{ $expense_entry->zonal_amount }}" required >
                                </div>

                                {{-- Рабочая зона --}}
                                <div class="form-group">
                                    <label>Валюта</label>
                                    <select name="new_currency" class="form-control">
                                        @foreach (array('MDL','USD','EUR') as $key => $value)
                                            <option value="{{ $value }}"
                                                    @if ($value == $expense_entry->zonal_currency )
                                                    selected="selected"
                                                    @endif
                                            >{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Основание</label>
                                    <input type="text" name="new_basis" class="form-control" value="{{ $expense_entry->zonal_basis }}" required >
                                </div>

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $expense_entry->zonal_description }}" required >
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-success">Сохранить</button>
                            </div>
                        </div>{{-- /modal-content --}}
                    </div>
                </div>
            </form>


        @endforeach
        </tbody>
    </table>


    {{-- Добавить расход денег : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addZonalExpenseModal">
        Добавить расход денег
    </button>

    {{-- Добавить зональный расход денег : Форма и модальное окно --}}
    <form action="{{ url('admin/assignments/view/'.$sub_assignment->id.'/management/add_zonal_assignment_expense') }}" method="POST">
        @csrf

        <div class="modal fade" id="addZonalExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить зональный расход денег</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                            {{-- ID наряда --}}
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                            <input type="hidden" name="assignment_id" value="{{ $sub_assignment->id }}">
                            

                            <div class="form-row">
                                {{-- Сумма --}}
                                <div class="form-group col-md-6">
                                    <label>Сумма</label>
                                    <input type="number" name="zonal_amount" min="0" class="form-control" required>
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
                                <input type="text" name="zonal_basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="zonal_description" class="form-control" required></textarea>
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
        @foreach($zonal_assignment_work as $work_entry)
            <tr>
                <td>
                    {{ $work_entry->zonal_basis }}<br>
                </td>
                <td>
                    {{ $work_entry->zonal_description }}<br>
                </td>
                <td>
                    {{ date('d m Y', $work_entry->created_at->timestamp) }}<br>
                </td>

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#work_entry{{$work_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>


            <form action="{{ url('/master/zonal_work_entry/'.$work_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="work_entry{{$work_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Редактировать список выполненых работ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="{{ $work_entry->id }}">

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Название</label>
                                    <input type="text" name="new_basis" class="form-control" value="{{ $work_entry->zonal_basis }}" required >
                                </div>

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $work_entry->zonal_description }}" required >
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-success">Сохранить</button>
                            </div>
                        </div>{{-- /modal-content --}}
                    </div>
                </div>

            </form>

        @endforeach
        </tbody>
    </table>

    {{-- Добавить список выполненных работ : Кнопка открытия модального окна --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addZonalWorksModal">
        Добавить список выполненных работ
    </button>

    {{-- Добавить зональную выполненую работу : Форма и модальное окно --}}
    <form action="{{ url('admin/assignments/view/'.$sub_assignment->id.'/management/add_zonal_assignment_works') }}" method="POST">
        @csrf

        <div class="modal fade" id="addZonalWorksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="hidden" name="sub_assignment_id" value="{{ $sub_assignment->id }}"> 

                            
                            {{-- Основание --}}
                            <div class="form-group">
                                <label>Основание (реквизиты документа или действие)</label>
                                <input type="text" name="zonal_basis" class="form-control" required>
                            </div>

                            {{-- Описание - не обязательно --}}
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="zonal_description" class="form-control" required></textarea>
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