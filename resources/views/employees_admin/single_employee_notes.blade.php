@extends('layouts.limitless')

@section('page_name')
    
@endsection

@section('content')

<h3>Примечания к сотруднику</h3>
    {{-- Вывод примечаний --}}
<div class="card card-p">
    <table class="table">
        <thead>
        <tr>
            <th>Текст</th>
            <th>Автор</th>
            <th></th>
        </tr>
        <tr>
            <a href="{{ url('admin/employee/add_note_to_employee/'.$employee->id) }}">
                <div  class="btn btn-primary mb-2">Добавить примечание</div>
            </a>
        </thead>

        <tbody>
        {{--  Вывод данных --}}
        @foreach($employee_notes as $employee_note)
            <tr>
                {{-- Текст примечания --}}
                <td>{{ $employee_note->text }}</td>

                {{-- Автор примечания --}}
                <td>{{ $employee_note->author_name }}</td>

                {{-- Кнопки управления --}}
                <td>
                    <a href="{{ url('admin/employee/edit_note/'.$employee_note->id) }}" ">Редактировать</a>
                </td>
                <td>
                    <a href="{{ url('admin/employee/delete_employee_note/'.$employee_note->id ) }}">
                        <div class="btn btn-danger">
                            X
                        </div>
                    </a>
                </td>

            </tr>
        @endforeach
        </tbody>

    </table>
    {{-- Конец таблицы --}}
</div>




@endsection