@extends('layouts.limitless')

@section('page_name')
Календарь

<a href="{{ url('/employee/calendar/'.$employee_id.'/test_late') }}">
    <button class="btn btn-info">Запись отсутствовал</button>
</a>
<a href="{{ url('/employee/calendar/'.$employee_id.'/test_ontime') }}">
    <button class="btn btn-info">Запись присутствовал</button>
</a>

@endsection

@section('content')
<style>

.fc-sun { background-color:lightgray;  }
.fc-event-container { background-color:lightgreen; }

</style>

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

<div class="col-lg-7 bg-white card card-outline-secondary p-3">
    <div id='calendar'></div>
</div>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        // page is now ready, initialize the calendar...
        $('#calendar').fullCalendar({
            firstDay: 1,
            monthNames: ['Январь','Февраль','Март','Апрель','Май','οюнь','οюль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','οюнь','οюль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
            dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
            dayNamesShort: ["ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ"],
            buttonText: {
                    prev: "Пред. месяц",
                    next: "След. месяц",
                    prevYear: "&nbsp;&lt;&lt;&nbsp;",
                    nextYear: "&nbsp;&gt;&gt;&nbsp;",
                    today: "Сегодня",
                    month: "Месяц",
                    week: "Неделя",
                    day: "День"
                },
            events : [
                @foreach($tasks as $task)
                {
                    title : '{{ $task->description }}',
                    start : '{{ $task->task_date }}',
                    //url : '{{ url('/employee/calendar_edit', $task->id) }}'
                },
                @endforeach
            ]
        })
    });
</script>



@endsection