@extends('layouts.limitless')

@section('page_name')
    Список клиентов 
    
      <input type="text" name="search" id="search" class="form-control" placeholder="Поиск клиента" />
    
@endsection

@section('content')

    <table class="table table-striped">
       <thead>
        <tr>
         <th>Общее имя</th>
        <!-- <th>ФИО</th>
         <th>Организация</th>
         <th>Номер телефона</th>
         <th>Баланс</th>
         <th>Скидка</th> -->
        </tr>
       </thead>
       <tbody>

       </tbody>
      </table>
    


    <!-- @foreach($clients as $client)
        <a href="{{ url('admin/view_client/'.$client->id) }}">
            {{ $client->general_name }}
        </a>
        <br>
    @endforeach -->
    <hr>
    <a href="{{ url('admin/add_client') }}">
        <div class="btn btn-primary">
            Добавить клиента
        </div>
    </a>

    <script>
        $(document).ready(function(){

         fetch_customer_data();

         function fetch_customer_data(query = '')
         {
          $.ajax({
           url:"{{ route('clients_search') }}",
           method:'GET',
           data:{query:query},
           dataType:'json',
           success:function(data)
           {
            $('tbody').html(data.table_data);            
           }
          })
         }

         $(document).on('keyup', '#search', function(){
          var query = $(this).val();
          fetch_customer_data(query);
         });
        });
    </script>
@endsection