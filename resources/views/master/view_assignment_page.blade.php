@extends('layouts.limitless')

@section('page_name')
    Наряд: {{ $assignment->description }}
@endsection

@section('content')
    {{-- Статическая информация по наряду --}}
    Клиент: {{ $assignment->client_name }} <br>
    Авто: {{ $assignment->car_name }}<br>
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

                {{-- url('admin/assignments/view/'.$assignment->id.'/management') --}}

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editCarModel{{$sub_assignment->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>

            <form action="{{ url('/master/redact_subassignments/'.$sub_assignment->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="editCarModel{{$sub_assignment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Редактировать зональный наряд</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="{{ $sub_assignment->id }}">

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Название</label>
                                    <input type="text" name="new_name" class="form-control" value="{{ $sub_assignment->name }}" required >
                                </div>

                                {{-- Рабочая зона --}}
                                <div class="form-group">
                                    <label>Рабочая зона</label>
                                    <select name="new_workzone" class="form-control">
                                        @foreach ($workzones as $key => $value)
                                            <option value="{{ $value }}"
                                                    @if ($value == $sub_assignment->workzone_name )
                                                    selected="selected"
                                                    @endif
                                            >{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
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

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editCarModelModal{{$income_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>

            <form action="{{ url('/master/income_entry/'.$income_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="editCarModelModal{{$income_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" name="new_amount" class="form-control" value="{{ $income_entry->amount }}" required >
                                </div>

                                {{-- Валюта --}}
                                <div class="form-group">
                                    <label>Валюта</label>
                                    <select name="new_currency" class="form-control">
                                        @foreach (array('UAH','USD','EUR') as $key => $value)
                                            <option value="{{ $value }}"
                                                    @if ($value == $income_entry->currency)
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
                                    <input type="text" name="new_basis" class="form-control" value="{{ $income_entry->basis  }}" required>
                                </div>

                                {{-- Описание --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $income_entry->description  }}" required>
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
    <!--<p>Сумма заходов: {{ $assignment_income->sum('amount') }}<br></p>-->



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

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editCar{{$expense_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>

            <form action="{{ url('/master/expense_entry/'.$expense_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="editCar{{$expense_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" name="new_amount" class="form-control" value="{{ $expense_entry->amount }}" required >
                                </div>

                                {{-- Рабочая зона --}}
                                <div class="form-group">
                                    <label>Валюта</label>
                                    <select name="new_currency" class="form-control">
                                        @foreach (array('UAH','USD','EUR') as $key => $value)
                                            <option value="{{ $value }}"
                                                    @if ($value == $expense_entry->currency )
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
                                    <input type="text" name="new_basis" class="form-control" value="{{ $expense_entry->basis }}" required >
                                </div>

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $expense_entry->description }}" required >
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
    <!--<p>Сумма расходов: {{ $assignment_expense->sum('amount') }}<br></p>-->





    {{-- Добавить расход денег : Кнопка открытия модального окна --}}
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

                <td>
                    {{-- Редактировать модель машны : Кнопка открытия модального окна --}}
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit{{$work_entry->id}}">
                        Редактировать
                    </button><br>
                </td>
            </tr>


            <form action="{{ url('/master/work_entry/'.$work_entry->id) }}" method="POST">
                @csrf

                <div class="modal fade" id="edit{{$work_entry->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" name="new_basis" class="form-control" value="{{ $work_entry->basis }}" required >
                                </div>

                                {{-- Название --}}
                                <div class="form-group">
                                    <label>Описание</label>
                                    <input type="text" name="new_description" class="form-control" value="{{ $work_entry->description }}" required >
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