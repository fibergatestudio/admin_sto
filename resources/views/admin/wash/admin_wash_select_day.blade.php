@extends('layouts.limitless')

@section('page_name')
    Отчет мойки
    <a href="{{ url('/admin/wash/select_date') }}"><button class="btn btn-warning">Назад</button></a>
@endsection

@section('content')
<div class="form-row">
    <div class="card card-outline-secondary col-md-12">
    {{ $year }}
    {{ $month }}

    <?php
$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
echo "There was $d days in $month 2019.";
?>
<?php 
echo '<div class="row">';
for ($i = 0; $i < $d; $i++)
    {
        $today = strtotime(date("Y-m-d"));
        $date = strtotime($year . "-" . $month . "-" . ($i + 1));
        $url = "/admin/wash/select_date/$year/$month/".($i + 1)."";
        if ($today === $date)
        {
            echo ("!!");
        }
        $day=strftime("%A", $date);
        echo '<div class="btn col-md-3">';
        echo '<a href="'.$url.'">';
        echo (($i + 1));
        echo '</a>';
        echo "</div>";
    }
    echo "</div>";

?>
    </div>
</div>
@endsection