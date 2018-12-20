@extends('layouts.employee_layout')

@section('content')
    <h2>Мои активные наряды</h2>
    <table class="table">
        @foreach($assignments as $assignment)
            <tr>
                {{-- Описание --}}
                <td>{{ $assignment->description }}</td>
                
                {{-- Управление : переход --}}
                <td>
                    <a href="#">
                        <div class="btn btn-primary">
                            Управление
                        </div>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    
    {{-- Архив нарядов : переход --}}
    <a href="{{ url('employee/my_assignments_archive') }}">
        <div class="btn btn-secondary">
            Архив моих нарядов
        </div>
    </a>
@endsection