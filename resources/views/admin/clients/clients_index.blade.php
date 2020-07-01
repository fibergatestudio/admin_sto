@extends('layouts.limitless')

@section('page_name')
    <a href="{{ url('admin/clients/add_client') }}">
        <div class="btn btn-primary">
            Добавить клиента
        </div>
    </a>
    
@endsection

@section('content')

<div class="card card-p">
    <table id="table_all" class="table table-striped">
        <thead>
        <tr>
            <th>ФИО</th>
            <th>Кол-во Авто</th>
            <th>Кол-во Нарядов</th>
            <th>Оплачено</th>
            <th>Скидка</th>
            <!-- <th>ФИО</th>
             <th>Организация</th>
             <th>Номер телефона</th>
             <th>Баланс</th>
             <th>Скидка</th> -->
        </tr>
        </thead>
        <tbody>
        
            @foreach($all_info as $info)
                    <tr>
                
                        <td> 
                            <a href="{{ url('admin/clients/view_client/'.$info['id']) }}">
                                {{ $info['fio'] }}
                            </a>
                        </td>
                        <td>
                            {{ $info['cars_count'] }}
                        </td>
                        <td>
                            {{ $info['assignment_count'] }}
                        </td>
                        <td>
                            {{ $info['total_income'] }}
                        </td>
                        <td>

                            @if($info['discount'] <= 0)
                                0 %
                            @else
                                {{ $info['discount'] }} %
                            @endif
                        
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
 $(function () {
    $("#table_all").DataTable({
      "order": [[ 2, 'desc' ]],
      "language":{
          "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json",
      }
   });
 });
</script>

    


    <!-- @foreach($clients as $client)
        <a href="{{ url('admin/view_client/'.$client->id) }}">
            {{ $client->general_name }}
        </a>
        <br>
    @endforeach -->
{{--    <hr>--}}
    

   <!-- <script>
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
    </script> -->
@endsection