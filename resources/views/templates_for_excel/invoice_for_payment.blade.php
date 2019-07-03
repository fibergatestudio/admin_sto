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
    <td><h5>Cont de plată Nr. {{'1557917579'}} din data de {{'24/11/2018'}}</h5></td>
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
    <td><h5>Beneficiar</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Întreprindere:</td>
    <td colspan="11">{{'Das Auto Service SRL'}}</td>
    <td colspan="4" style="font-weight: bold;">Adresa juridică:</td>
    <td colspan="11">{{'mun. Chișinau, str. Uzinelor 88'}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Banca:</td>
    <td colspan="11">{{'BC “Moldindconbank” S.A Chisinau, Filiala Kiev'}}</td>
    <td colspan="4" style="font-weight: bold;">IBAN:</td>
    <td colspan="11">{{'MD26ML000000002251636610'}}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Codul Băncii:</td>
    <td colspan="11">{{'MOLDMD2X336'}}</td>
    <td colspan="4" style="font-weight: bold;">Cod Fiscal / Cod TVA:</td>
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
    <td><h5>Autoturism</h5></td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Marca:</td>
    <td colspan="11">{{ $car_data[0]->brand }}</td>
    <td colspan="4" style="font-weight: bold;">Anul producerii:</td>
    <td colspan="11">{{ $assignment_data[0]->release_year }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Model:</td>
    <td colspan="11">{{ $car_data[0]->model }}</td>
    <td colspan="4" style="font-weight: bold;">VIN:</td>
    <td colspan="11">{{ $assignment_data[0]->vin_number }}</td>
  </tr>
  <tr>
    <td colspan="4" style="font-weight: bold;">Nr. inmatriculare:</td>
    <td colspan="11">{{ $assignment_data[0]->reg_number }}</td>
    <td colspan="4" style="font-weight: bold;">Parcurs:</td>
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
    <td><h5>Servicii prestate</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td style="font-weight: bold;">№</td>
      <td colspan="13" style="text-align: left; font-weight: bold;">Denumirea</td>
      <td colspan="2" style="font-weight: bold;">Mas.</td>
      <td colspan="2" style="font-weight: bold;">Cant.</td>
      <td colspan="3" style="font-weight: bold;">Preț fără TVA</td>
      <td colspan="3" style="font-weight: bold;">Suma fără TVA</td>
      <td colspan="3" style="font-weight: bold;">TVA</td>
      <td colspan="3" style="font-weight: bold;">Total</td>
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
        <td colspan="2">{{ 'serv.' }}</td>

        {{-- Количество --}}
        <td colspan="2">{{ $assignment->d_table_quantity }}</td>

        {{-- Цена без НДС --}}

        <?php
        $coefficient = 1;
        if ($assignment->d_table_currency === 'USD') {
          $coefficient = $currency[0]->usd;
        }
        elseif ($assignment->d_table_currency === 'EUR') {
          $coefficient = $currency[0]->eur;
        }
        ?>

        <td colspan="3">{{ round(((int)$assignment->d_table_price/$coefficient),2) }}</td>

        {{-- Сумма без НДС --}}
        <td colspan="3">{{ round(((int)$assignment->work_sum_row/$coefficient),2) }}</td>

        {{-- НДС --}}
        <td colspan="3">
        {{ 0 }}
        </td>

        {{-- Итого --}}
        <td colspan="3">
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
      <td colspan="21" style="text-align: left; font-weight: bold;">Total</td>
      <td colspan="3" style="font-weight: bold;">{{ $sum_work }}</td>
      <td colspan="3" style="font-weight: bold;">{{ 0 }}</td>
      <td colspan="3" style="font-weight: bold;">{{ $sum_work }}</td>
    </tr>
  </tfoot>
</table>


<table>
  <tr>
      <td colspan="12"></td>
      <td><h5>Materiale / Piese folosite</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td style="font-weight: bold;">№</td>
      <td colspan="13" style="text-align: left; font-weight: bold;">Denumirea</td>
      <td colspan="2" style="font-weight: bold;">Mas.</td>
      <td colspan="2" style="font-weight: bold;">Cant.</td>
      <td colspan="3" style="font-weight: bold;">Preț fără TVA</td>
      <td colspan="3" style="font-weight: bold;">Suma fără TVA</td>
      <td colspan="3" style="font-weight: bold;">TVA</td>
      <td colspan="3" style="font-weight: bold;">Total</td>
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
        <td colspan="2">{{ 'buc.' }}</td>

        {{-- Количество --}}
        <td colspan="2">{{ $assignment->d_table_spares_quantity }}</td>

        {{-- Цена без НДС --}}

        <?php
        $coefficient_2 = 1;
        if ($assignment->d_table_spares_currency === 'USD') {
          $coefficient_2 = $currency[0]->usd;
        }
        elseif ($assignment->d_table_spares_currency === 'EUR') {
          $coefficient_2 = $currency[0]->eur;
        }
        ?>

        <td colspan="3">{{ round(((int)$assignment->d_table_spares_price/$coefficient_2),2) }}</td>

        {{-- Сумма без НДС --}}
        <td colspan="3">{{ round(((int)$assignment->spares_sum_row/$coefficient_2),2) }}</td>

        {{-- НДС --}}
        <td colspan="3">
        {{ '0.00' }}
        </td>

        {{-- Итого --}}
        <td colspan="3">
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
      <td colspan="21" style="text-align: left; font-weight: bold;">Total</td>
      <td colspan="3" style="font-weight: bold;">{{ $sum_spares }}</td>
      <td colspan="3" style="font-weight: bold;">{{ 0 }}</td>
      <td colspan="3" style="font-weight: bold;">{{ $sum_spares }}</td>
    </tr>
  </tfoot>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="26" class="export-total" style="font-weight: bold;">Suma fară TVA ( MDL )</td>
    <td></td>
    <td colspan="3" class="export-total" style="font-weight: bold;">{{ $sum_work + $sum_spares }}</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="26" class="export-total" style="font-weight: bold;">TVA ( MDL )</td>
    <td></td>
    <td colspan="3" class="export-total" style="font-weight: bold;">{{ 0 }}</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="26" class="export-total" style="font-weight: bold;">Total incl. TVA ( MDL )</td>
    <td></td>
    <td colspan="3" class="export-total" style="font-weight: bold;">{{ $sum_work + $sum_spares }}</td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr class="export-note">
    <td colspan="30" style="font-weight: bold;">Notă</td>
  </tr>
  <tr class="export-note-txt">
    <td colspan="7" style="font-weight: bold;">Suma în litere:</td>
    <td colspan="23">{{ $total_in_words }} (incl. TVA)</td>
  </tr>
  <tr class="export-note-txt">
    <td colspan="7" style="font-weight: bold;">Destinația plății:</td>
    <td colspan="23">Pentru servicii de reparație a autovehiculelor</td>
  </tr>
</table>
