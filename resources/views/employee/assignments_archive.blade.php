@extends('layouts.limitless')

@section('page_name')
Архив моих нарядов
<hr>
<a href="{{ url('/employee/assignments/my_assignments') }}" class="btn btn-danger">Вернуться</a>
@endsection

@section('content')
    <table class="table">
        @foreach($assignments_archive as $assignment)
            <tr>
                {{-- Описание --}}
                <td>{{ $assignment->description }}</td>

                <td>{{ $assignment->status }}</td>
                
                {{-- Управление : переход --}}
                <td>
                    <a href="{{ url('/employee/assignments/manage_assignment/'.$assignment->id) }}">
                        <div class="btn btn-primary">
                            Управление
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
@endsection