@extends('layouts.limitless')

@section('page_name')
Финансы
@endsection


@section('content')
    Оплата за смены, ожидает подтверждения<br>
    <table class="table">
        @foreach($pending_shifts as $shift)
            <tr>
                {{-- Дата --}}
                <td>
                    {{-- ... --}}
                </td>

                {{-- Время открытия --}}
                <td>
                    {{-- ... --}}
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