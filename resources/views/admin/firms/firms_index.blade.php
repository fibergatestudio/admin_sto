@extends('layouts.limitless')

@section('page_name')
    Список фирм 
    <a href="{{ url('admin/clients/add_client') }}">
        <div class="btn btn-primary">
            Добавить фирму
        </div>
    </a>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addZonalExpenseModal">
        Добавить Фирму
    </button>

    <form action="{{ url('admin/firms/firms_index/add_firm') }}" method="POST">
        @csrf

        <div class="modal fade" id="addZonalExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить Фирму</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <label>Название</label>
                            <input type="text" name="firm_name"  class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Скидка </label>
                            <input type="number" name="firm_discount" min="0" max="25" class="form-control" required>
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

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Фирма</th>
                <th>Скидка</th>

                <th></th>{{-- Кнопки управления --}}
            </tr>
        </thead>
        <tbody>
            @foreach($firms as $firm)
            <tr>
                <td>{{ $firm->firm_name }}</td>
                <td>{{ $firm->firm_discount }}%</td>
            </tr>
            @endforeach
        </tbody>
      </table>
    
    <hr>
    
@endsection