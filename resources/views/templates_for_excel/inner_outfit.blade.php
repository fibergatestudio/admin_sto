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
    <td colspan="4"><h6>Марка:</h6></td>
    <td colspan="11">{{'Honda'}}</td>
    <td colspan="4"><h6>Год:</h6></td>
    <td colspan="11">{{''}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Модель:</h6></td>
    <td colspan="11">{{'Civic'}}</td>
    <td colspan="4"><h6>VIN:</h6></td>
    <td colspan="11">{{'19XFC1F79GE209458'}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Рег. номер:</h6></td>
    <td colspan="11">{{''}}</td>
    <td colspan="4"><h6>Пробег:</h6></td>
    <td colspan="11">{{''}}</td>
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
      <td colspan="6"><h6>Работник</h6></td>
      <td colspan="9"><h6>Наименование работ</h6></td>
      <td colspan="5"><h6>Срок выполнения</h6></td>
      <td colspan="5"><h6>Роспись работника</h6></td>
      <td colspan="5"><h6>Проверил работу</h6></td>
    </tr>
  </thead>    
  <tbody class="export-data">
  @foreach($assignments as $assignment)
    <tr height="30">

        {{-- Работник --}}
        <td colspan="6"></td>

        {{-- Наименование работ --}}
        <td colspan="9" style="vertical-align: middle; text-align: left;">{{ 'Демонтаж-Монтаж - АВТО (1 шт.)' }}</td>

        {{-- Срок выполнения --}}
        <td colspan="5"></td>

        {{-- Роспись работника --}}
        <td colspan="5"></td>

        {{-- Проверил работу --}}
        <td colspan="5"></td>

    </tr>
  @endforeach           
  </tbody>
</table>




