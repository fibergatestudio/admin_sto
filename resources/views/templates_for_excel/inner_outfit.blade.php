<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>
<link href="{{asset('css/table-export-excel.css')}}" rel="stylesheet" type="text/css">


<table>
  <tr><td colspan="8"><img src="excel/logo-sto.png"></td></tr>
  <tr>
    @for($i=0; $i < 30; $i++)
    <td width="4"></td>
    @endfor
  </tr>
  <tr>
    <td colspan="9"></td>
    <td><h5>Внутренний наряд {{'1557917579'}} от {{'24/11/2018'}}</h5></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>    
  <tr class="export-line">
    <td colspan="30" height="1"></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="13"></td>
    <td><h5>Автомобиль</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Марка:</td>
    <td colspan="11">{{ $car_data[0]->brand }}</td>
    <td colspan="4" style="font-weight: bold;">Год:</td>
    <td colspan="11">{{ $assignment_data[0]->release_year }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Модель:</td>
    <td colspan="11">{{ $car_data[0]->model }}</td>
    <td colspan="4" style="font-weight: bold;">VIN:</td>
    <td colspan="11">{{ $assignment_data[0]->vin_number }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Рег. номер:</td>
    <td colspan="11">{{ $assignment_data[0]->reg_number }}</td>
    <td colspan="4" style="font-weight: bold;">Пробег:</td>
    <td colspan="11">{{ $assignment_data[0]->mileage_km }}</td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>    
  <tr class="export-line">
    <td colspan="30" height="1"></td>
  </tr>
</table>


<table>
  <tr>
    <td colspan="13"></td>
    <td><h5>Работы</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td colspan="6" style="font-weight: bold;">Работник</td>
      <td colspan="9" style="font-weight: bold;">Наименование работ</td>
      <td colspan="5" style="font-weight: bold;">Срок выполнения</td>
      <td colspan="5" style="font-weight: bold;">Роспись работника</td>
      <td colspan="5" style="font-weight: bold;">Проверил работу</td>
    </tr>
  </thead>    
  <tbody class="export-data">
  @foreach($assignment_data as $assignment)
  @if(!empty($assignment->work_row_index))
    <tr height="30">

        {{-- Работник --}}
        <td colspan="6"></td>

        {{-- Наименование работ --}}
        <td colspan="9" style="vertical-align: middle; text-align: left;">{{ $assignment->d_table_list_completed_works.' - '.$assignment->d_table_work_direction.' ('.$assignment->d_table_quantity.' шт.)' }}</td>

        {{-- Срок выполнения --}}
        <td colspan="5"></td>

        {{-- Роспись работника --}}
        <td colspan="5"></td>

        {{-- Проверил работу --}}
        <td colspan="5"></td>

    </tr>
  @endif  
  @endforeach           
  </tbody>
</table>




