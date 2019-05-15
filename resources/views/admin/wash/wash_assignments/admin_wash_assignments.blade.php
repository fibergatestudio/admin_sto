@extends('layouts.limitless')

@section('page_name')
    Наряды Мойка
    <a href="{{ url('/admin/wash_assignments/create_assignment_index')}}"><button class="btn btn-primary">Добавить наряд</button></a>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAssignment">
    Поиск
    </button>
            <div style="margin:0;"class="modal fade modal-dialog" id="addAssignment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Поиск</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('/admin/wash_assignments/search/') }}" method="POST">
                            @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select class="form-control" type="text" name="wash_client_id">
                                        @foreach ($wash_clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="{{ url('admin/cars_in_service/add') }}">
                                            <button type="submit" class="btn btn-large btn-info">
                                                Искать
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>{{-- /modal-content --}}
                </div>{{-- /modal-dialog --}}
            </div>{{-- /modal fade --}}
@endsection

@section('content')

<style>

tr:hover { background: #26a69957; }
td a { 
    display: block; 
    border: 1px solid black;
    padding: 16px; 
}

</style>

<div class="form-row">
    <div class="card card-outline-secondary col-md-12">

            <div class="card-header">
                <h3 class="mb-0">Список Нарядов Мойка</h3>
            </div>
        
            <hr>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th>Номер наряда</th>
                        <th>Клиент</th>
                        <th>Заметка</th>
                        <th>Дата</th>
                        <th></th>
                    </tr>
                </thead>
            <tbody >
                @foreach($car_wash as $wash_assgin)
                    <tr>
                        <th>{{ $wash_assgin->id }}</th>
                        <th>{{ $wash_assgin->car_wash_client_name }}</th>
                        <th>{{ $wash_assgin->print_settings_id }}</th>
                        <th>
                            <?php 
                                $date = date('d-m-Y', strtotime($wash_assgin->created_at)); 
                                echo $date;
                            ?>
                        </th>
                        <th>
                            <a href="{{ url('/admin/wash_assignments/'.$wash_assgin->id) }}"> 
                                <button class="btn brn-primary">Ред</button>
                            </a>
                        </th>
                    </tr>
                @endforeach
                <!-- <tr onclick="document.location = '/admin/wash_assignments/id';">
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                </tr> -->
            </tbody>
            </table>
    
    </div>
</div>

<script>


</script>

@endsection