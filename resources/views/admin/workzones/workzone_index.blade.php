@extends('layouts.limitless')

@section('page_name')
Рабочие зоны
{{-- Добавить пост: переход --}}
<a href="{{ url('admin/workzones/add') }}">
    <div class="btn btn-primary">
        Добавить рабочий пост
    </div>
</a>
@endsection

@section('content')

<table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Направление работ</th>
                <th>Описание</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($workzones as $workzone)
        <tr>
            <td style="width: 35px;">
                @if (!empty($workzone->workzone_color ))
                <i style="width:35px; height:35px; display:flex;background-color:{{ $workzone->workzone_color }}; border: 2px solid rgb(97, 97, 97);"></i>
                @else
                null
                @endif
            </td>
            <td>
            {{ $workzone->general_name }}
            </td>
            <td>
            {{ $workzone->works_direction }}
            </td>
            <td>
            {{ $workzone->description }}
            </td>
            <td>
                <a href="{{ url('admin/workzones/edit/'.$workzone->id) }}">
                    <div class="btn btn-light" style="margin-left: 10px">
                        Изменить информацию
                    </div>
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
</table>

<!-- 
@foreach($workzones as $workzone)
<div class="container">
    <div class="row">
        <div class="col">
        <i style="width:35px; height:35px; display:flex;background-color:{{ $workzone->workzone_color }};"></i>
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
</div> -->

 <!--<div class="col">
     {{-- Кнопка удалить --}}
      <a href="{{ url('admin/workzones/delete/'.$workzone->id) }}">
        <div class="btn btn-warning" style="margin-left: 10px">
            Удалить
        </div>
    </a>

</div>-->
<!-- </div>
</div>
@endforeach -->
<hr>

@endsection