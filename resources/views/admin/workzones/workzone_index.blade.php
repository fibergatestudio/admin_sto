@extends('layouts.limitless')

@section('page_name')
Рабочие зоны
@endsection

@section('content')
@foreach($workzones as $workzone)
<div class="container">
    <div class="row">
        <div class="col">
         {{ $workzone->general_name }}
         <br>
     </div>
     <div class="col">
      {{-- Кнопка изменить --}}
      <a href="{{ url('admin/workzones/edit/'.$workzone->id) }}">
        <div class="btn btn-light" style="margin-left: 10px">
            Изменить название
        </div>
    </a>

</div>
 <!--<div class="col">
     {{-- Кнопка удалить --}}
      <a href="{{ url('admin/workzones/delete/'.$workzone->id) }}">
        <div class="btn btn-warning" style="margin-left: 10px">
            Удалить
        </div>
    </a>

</div>-->
</div>
</div>
@endforeach
<hr>
{{-- Добавить пост: переход --}}
<a href="{{ url('admin/workzones/add') }}">
    <div class="btn btn-primary">
        Добавить рабочий пост
    </div>
</a>
@endsection