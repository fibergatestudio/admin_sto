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
    <td colspan="4"><h6>Întreprindere:</h6></td>
    <td colspan="11">{{'Das Auto Service SRL'}}</td>
    <td colspan="4"><h6>Adresa juridică:</h6></td>
    <td colspan="11">{{'mun. Chișinau, str. Uzinelor 88'}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Banca:</h6></td>
    <td colspan="11">{{'BC “Moldindconbank” S.A Chisinau, Filiala Kiev'}}</td>
    <td colspan="4"><h6>IBAN:</h6></td>
    <td colspan="11">{{'MD26ML000000002251636610'}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Codul Băncii:</h6></td>
    <td colspan="11">{{'MOLDMD2X336'}}</td>
    <td colspan="4"><h6>Cod Fiscal / Cod TVA:</h6></td>
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
    <td colspan="4"><h6>Marca:</h6></td>
    <td colspan="11">{{'Honda'}}</td>
    <td colspan="4"><h6>Anul producerii:</h6></td>
    <td colspan="11">{{''}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Model:</h6></td>
    <td colspan="11">{{'Civic'}}</td>
    <td colspan="4"><h6>VIN:</h6></td>
    <td colspan="11">{{'19XFC1F79GE209458'}}</td>
  </tr>
  <tr>
    <td colspan="4"><h6>Nr. inmatriculare:</h6></td>
    <td colspan="11">{{''}}</td>
    <td colspan="4"><h6>Parcurs:</h6></td>
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
    <td><h5>Servicii prestate</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <thead class="export-data">
    <tr height="20">
      <td><h6>№</h6></td>
      <td colspan="13"><h6 style="text-align: left;">Denumirea</h6></td>
      <td colspan="2"><h6>Mas.</h6></td>
      <td colspan="2"><h6>Cant.</h6></td>
      <td colspan="3"><h6>Preț fără TVA</h6></td>
      <td colspan="3"><h6>Suma fără TVA</h6></td>
      <td colspan="3"><h6>TVA</h6></td>
      <td colspan="3"><h6>Total</h6></td>
    </tr>
  </thead>    
  <tbody class="export-data">
  <?php $i = 1; ?>
  @foreach($assignments as $assignment)
    <tr>
        {{-- Номер --}}
        <td>{{ $i }}</td>

        {{-- Название работы и рабочей зоны --}}
        <td style="text-align: left;" colspan="13">{{ beautify_date($assignment->date_of_creation) }}</td>

        {{-- Единица измерения --}}
        <td colspan="2">{{ 'serv.' }}</td>

        {{-- Количество --}}
        <td colspan="2">{{ $assignment->responsible_employee_id }}</td>

        {{-- Цена без НДС --}}
        <td colspan="3">@if (!empty($assignment->created_at ))
          {{ $assignment->created_at }}
        @endif</td>

        {{-- Сумма без НДС --}}
        <td colspan="3">{{ $assignment->status }}</td>

        {{-- НДС --}}
        <td colspan="3">
        {{ $assignment->order }}                
        </td>

        {{-- Итого --}}
        <td colspan="3">
          {{ $assignment->status }}
        </td>
    </tr>
    <?php $i++; ?>
  @endforeach           
  </tbody>
  <tfoot class="export-data">
    <tr>
      <td colspan="21" style="text-align: left;"><h6>Total</h6></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
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
      <td><h6>№</h6></td>
      <td colspan="13"><h6 style="text-align: left;">Denumirea</h6></td>
      <td colspan="2"><h6>Mas.</h6></td>
      <td colspan="2"><h6>Cant.</h6></td>
      <td colspan="3"><h6>Preț fără TVA</h6></td>
      <td colspan="3"><h6>Suma fără TVA</h6></td>
      <td colspan="3"><h6>TVA</h6></td>
      <td colspan="3"><h6>Total</h6></td>
    </tr>
  </thead>
  <tbody class="export-data">
    <?php $i = 1; ?>
    @foreach($assignments as $assignment)
    <tr>
        {{-- Номер --}}
        <td>{{ $i }}</td>

        {{-- Название детали --}}
        <td style="text-align: left;" colspan="13">{{ beautify_date($assignment->date_of_creation) }}</td>

        {{-- Единица измерения --}}
        <td colspan="2">{{ 'buc.' }}</td>

        {{-- Количество --}}
        <td colspan="2">{{ $assignment->responsible_employee_id }}</td>

        {{-- Цена без НДС --}}
        <td colspan="3">{{ $assignment->created_at }}</td>

        {{-- Сумма без НДС --}}
        <td colspan="3">{{ $assignment->status }}</td>

        {{-- НДС --}}
        <td colspan="3">
        {{ $assignment->order }}                
        </td>

        {{-- Итого --}}
        <td colspan="3">
          {{ $assignment->status }}
        </td>
    </tr>
    <?php $i++; ?>
  @endforeach 
  </tbody>
  <tfoot class="export-data">
    <tr>
      <td colspan="21" style="text-align: left;"><h6>Total</h6></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
      <td colspan="3"><h5>{{'0405358'}}</h5></td>
    </tr>
  </tfoot>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="27" class="export-total"><h6>Suma fară TVA ( MDL )</h6></td>
    <td colspan="3" class="export-total"><h5>{{'0405358'}}</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="27" class="export-total"><h6>TVA ( MDL )</h6></td>
    <td colspan="3" class="export-total"><h5>{{'0405358'}}</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td colspan="27" class="export-total"><h6>Total incl. TVA ( MDL )</h6></td>
    <td colspan="3" class="export-total"><h5>{{'0405358'}}</h5></td>
  </tr>
  <tr>
    <td height="3"></td>
  </tr>
  <tr class="export-note">
    <td colspan="30"><h6>Notă</h6></td>
  </tr>
  <tr class="export-note-txt">
    <td colspan="7"><h6>Suma în litere:</h6></td>
    <td colspan="23">Patruzeci și mii nouă sute treizeci și nouă lei 21 bani (incl. TVA)</td>
  </tr>
  <tr class="export-note-txt">
    <td colspan="7"><h6>Destinația plății:</h6></td>
    <td colspan="23">Pentru servicii de reparație a autovehiculelor</td>
  </tr>
</table>




