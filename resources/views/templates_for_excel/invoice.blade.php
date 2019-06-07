<?php
    function beautify_date($mysql_date){
        $date_dump = explode('-', $mysql_date);
        return $date_dump[2].'/'.$date_dump[1].'/'.$date_dump[0];
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
    <td><h5>INVOICE Nr. {{'1557917579'}} from {{'24/11/2018'}}</h5></td>
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
    <td><h5>Seller</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Company:</td>
    <td colspan="11">{{'Das Auto Service SRL'}}</td>
    <td colspan="4" style="font-weight: bold;">Address:</td>
    <td colspan="11">{{'mun. Chișinau, str. Uzinelor 88'}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Bank:</td>
    <td colspan="11">{{'BC “Moldindconbank” S.A Chisinau, Filiala Kiev'}}</td>
    <td colspan="4" style="font-weight: bold;">IBAN:</td>
    <td colspan="11">{{'MD26ML000000002251636610'}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">SWIFT:</td>
    <td colspan="11">{{'MOLDMD2X336'}}</td>
    <td colspan="4" style="font-weight: bold;">Fiscal Code / VAT:</td>
    <td colspan="11">{{'1016600017879 / 0405358'}}</td>
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
    <td><h5>Buyer</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Company:</td>
    <td colspan="11">{{''}}</td>
    <td colspan="4" style="font-weight: bold;">Address:</td>
    <td colspan="11">{{''}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Bank:</td>
    <td colspan="11">{{''}}</td>
    <td colspan="4" style="font-weight: bold;">IBAN:</td>
    <td colspan="11">{{''}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">SWIFT:</td>
    <td colspan="11">{{''}}</td>
    <td colspan="4" style="font-weight: bold;">VAT:</td>
    <td colspan="11">{{''}}</td>
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
    <td><h5>Car info</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Make:</td>
    <td colspan="11">{{ $car_data[0]->brand }}</td>
    <td colspan="4" style="font-weight: bold;">Year:</td>
    <td colspan="11">{{ $assignment_data[0]->release_year }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Model:</td>
    <td colspan="11">{{ $car_data[0]->model }}</td>
    <td colspan="4" style="font-weight: bold;">VIN:</td>
    <td colspan="11">{{ $assignment_data[0]->vin_number }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Plate number:</td>
    <td colspan="11">{{ $assignment_data[0]->reg_number }}</td>
    <td colspan="4" style="font-weight: bold;">Mileage:</td>
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
    <td><h5>Services provided</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td style="font-weight: bold;">№</td>
      <td colspan="13" style="text-align: left; font-weight: bold;">Description</td>
      <td colspan="3" style="font-weight: bold;">Unit</td>
      <td colspan="3" style="font-weight: bold;">Quantity</td>
      <td colspan="5" style="font-weight: bold;">Price (EUR)</td>
      <td colspan="5" style="font-weight: bold;">Total (EUR)</td>
    </tr>
  </thead>    
  <tbody class="export-data">
    <?php 
  $i = 1;
  $sum_work = 0;
   ?>
  @foreach($assignment_data as $assignment)
  @if(!empty($assignment->work_row_index))
    <tr>
        {{-- Номер --}}
        <td>{{ $i }}</td>

        {{-- Название работы и рабочей зоны --}}
        <td style="text-align: left;" colspan="13">{{ $assignment->d_table_list_completed_works.' - '.$assignment->d_table_work_direction }}</td>

        {{-- Единица измерения --}}
        <td colspan="3">{{ 'serv.' }}</td>

        {{-- Количество --}}
        <td colspan="3">{{ $assignment->d_table_quantity }}</td>

        {{-- Цена без НДС --}}
        
        <?php 
        $coefficient = 1;
        if ($assignment->d_table_currency === 'USD') {
          $coefficient = 1/$currency[0]->eur*$currency[0]->usd;
        }
        elseif ($assignment->d_table_currency === 'MDL') {
          $coefficient = 1/$currency[0]->eur;
        }
        ?>
        
        <td colspan="5">{{ round(((int)$assignment->d_table_price/$coefficient),2) }}</td>

        {{-- Итого --}}
        <td colspan="5">
          {{ round(((int)$assignment->work_sum_row/$coefficient),2) }}
        </td>
    </tr>
    <?php 
    $i++;
    $sum_work += round(((int)$assignment->work_sum_row/$coefficient),2);
     ?>
  @endif  
  @endforeach           
  </tbody>
  <tfoot class="export-data">
    <tr>
      <td colspan="25" style="text-align: left;font-weight: bold;">Total</td>
      <td colspan="5" style="font-weight: bold;">{{ $sum_work }}</td>
    </tr>
  </tfoot> 
</table>


<table>
  <tr>
      <td colspan="13"></td>
      <td><h5>Parts used</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td style="font-weight: bold;">№</td>
      <td colspan="13" style="text-align: left;font-weight: bold;">Description</td>
      <td colspan="3" style="font-weight: bold;">Unit</td>
      <td colspan="3" style="font-weight: bold;">Quantity</td>
      <td colspan="5" style="font-weight: bold;">Price (EUR)</td>
      <td colspan="5" style="font-weight: bold;">Total (EUR)</td>
    </tr>
  </thead>
  <tbody class="export-data">
    <?php 
    $i = 1;
    $sum_spares = 0; 
    ?>
    @foreach($assignment_data as $assignment)
    @if(!empty($assignment->spares_row_index))
    <tr>
        {{-- Номер --}}
        <td>{{ $i }}</td>

        {{-- Название детали --}}
        <td style="text-align: left;" colspan="13">{{ $assignment->d_table_spares_detail }}</td>

        {{-- Единица измерения --}}
        <td colspan="3">{{ 'buc.' }}</td>

        {{-- Количество --}}
        <td colspan="3">{{ $assignment->d_table_spares_quantity }}</td>

        {{-- Цена без НДС --}}

        <?php 
        $coefficient_2 = 1;
        if ($assignment->d_table_spares_currency === 'USD') {
          $coefficient_2 = 1/$currency[0]->eur*$currency[0]->usd;
        }
        elseif ($assignment->d_table_spares_currency === 'MDL') {
          $coefficient_2 = 1/$currency[0]->eur;
        }
        ?>
        
        <td colspan="5">{{ round(((int)$assignment->d_table_spares_price/$coefficient_2),2) }}</td>

        {{-- Итого --}}
        <td colspan="5">
          {{ round(((int)$assignment->spares_sum_row/$coefficient_2),2) }}
        </td>
    </tr>
    <?php 
    $i++; 
    $sum_spares += round(((int)$assignment->spares_sum_row/$coefficient_2),2);
    ?>
  @endif
  @endforeach 
  </tbody>
  <tfoot class="export-data">
    <tr>
      <td colspan="25" style="text-align: left;font-weight: bold;">Total</td>
      <td colspan="5" style="font-weight: bold;">{{ $sum_spares }}</td>
    </tr>
  </tfoot>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="26" class="export-total" style="font-weight: bold;">Total amount (EUR)</td>
    <td></td>
    <td colspan="3" class="export-total" style="font-weight: bold;">{{ $sum_work + $sum_spares }}</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="26" class="export-total" style="font-weight: bold;">Total VAT (EUR)</td>
    <td></td>
    <td colspan="3" class="export-total" style="font-weight: bold;">{{ 0 }}</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr class="export-note">
    <td colspan="30" style="font-weight: bold;">Note</td>
  </tr>
  <tr class="export-note-txt">
    <td colspan="7" style="font-weight: bold;">Destination of payment:</td>
    <td colspan="23">For car repair services</td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
  <tr>
    <td class="export-txt" colspan="15">BUYER</td>
    <td class="export-txt" colspan="15">SELLER</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
    <tr>
    <td class="export-txt" colspan="15">STAMP</td>
    <td class="export-txt" colspan="15">STAMP</td>
  </tr>
</table>





