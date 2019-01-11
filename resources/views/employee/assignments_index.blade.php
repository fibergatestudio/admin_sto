@extends('layouts.limitless')

@section('page_name')
Мои активные наряды
@endsection

@section('content')
    <table class="table">
        @foreach($assignments as $assignment)
            <tr>
                {{-- Описание --}}
                <td>{{ $assignment->description }}</td>
                
                {{-- Управление : переход --}}
                <td>
                    <a href="{{ url('/employee/manage_assignment/'.$assignment->id) }}">
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