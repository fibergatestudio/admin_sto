<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'.'.$date_dump[1].'.'.$date_dump[0];
    }
?>
<link href="{{asset('css/table-export-excel.css')}}" rel="stylesheet" type="text/css">


<table>
  <tr height="1">
    @for($i=0; $i < 30; $i++)
    <td width="4"></td>
    @endfor
  </tr>
  <tr>
    <td colspan="22" rowspan="5"><img src="excel/logo-sto.png"></td>
    <td class="export-head" colspan="8">Formular tipizat / Типовая форма</td>
  </tr>
  <tr>
    <td colspan="22"></td>
    <td colspan="8">Anexa nr.1 la Ordinul Ministerului Finanțelor</td>
  </tr>
  <tr>
    <td colspan="22"></td>
    <td colspan="8">al Republicii Moldova nr.118 din 28.08.2017</td>
  </tr>
  <tr>
    <td colspan="22"></td>
    <td colspan="8">Приложение № 1 к Приказу Министерства финансов</td>
  </tr>
  <tr>
    <td colspan="22"></td>
    <td colspan="8">Республики Молдова № 118 от 28.08.2017 г.</td>
  </tr>
</table>




