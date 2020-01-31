@extends('layouts.limitless')

@section('page_name')

{{-- Архив нарядов : переход --}}
    <a href="{{ url('employee/assignments/my_assignments_archive') }}">
        <div class="btn btn-secondary">
            Архив моих нарядов
        </div>
    </a>
@endsection

@section('content')
    <div class="card card-p">
        <table class="table">
            @foreach($assignments as $assignment)
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
    </div>
@endsection