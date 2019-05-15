@extends('layouts.limitless')

@section('page_name')
    Результаты поиска
    <a href="{{ url('/admin/wash_assignments/') }}"><button class="btn btn-warning">Назад</button></a>
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
                <h3 class="mb-0">Результат поиска по клиенту #{{ $wash_client_id }}</h3>
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
                @foreach($car_wash_result as $wash_assgin)
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