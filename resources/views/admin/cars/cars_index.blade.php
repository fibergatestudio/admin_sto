@extends('layouts.limitless')

@section('page_name')
Модели
@endsection


@section('content')
    Список моделей<br>
    <table class="table">
    <a>Test<a><br>
        @foreach($car_models as $model)
            <tr>
                {{-- Дата --}}
                <td>
                {{ $model->brand }}<br>
                </td>

                {{-- Время открытия --}}
                <td>
                <a>Test<a><br>
                </td>

                {{-- Время закрытия --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Сумма --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Кнопки контроля --}}
                <td>
                    {{-- Применить --}}
                    {{-- ... --}}

                    {{-- Изменить --}}
                    {{-- ... --}}

                    {{-- Кнопки "удалить" нету --}}
                    {{-- Вместо неё - изменить сумму на 0 --}}
                    {{-- Такая схема выбрана для того, чтобы сохранить целостность отчётности --}}

                </td>
            </tr>
        @endforeach
    </table>
@endsection