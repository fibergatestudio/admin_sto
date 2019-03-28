<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Assignment;
use App\Shift;

use App\Assignments_income;
use App\Assignments_expense;
use App\Assignments_completed_works;
use App\Coffee_token_log;
use App\Employee_balance_logs;
use App\Employee;

use Telegram\Bot\Laravel\Facades\Telegram;

use App\Supply_order;
use App\Supply_order_item;


class Employee_Dashboard_Controller extends Controller
{
    /* Главная страница для ЛК сотрудника */
    public function index(){
        return view('employee.employee_dashboard_index');
    }
    /***** МОЙ ПРОФИЛЬ *****/

    public function employee_profile(){

        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;

        $employee_edit = DB::table('employees')->where('id', $employee_id)->first();

        return view('employee.employee_profile',
        [
            'employee_edit' => $employee_edit,
            'employee' => $employee_id
        ]);

        // View::share('employee_edit', $employee_edit);
    }
    /***** ИСТОРИЯ ФИНАНСОВ *****/ 

       public function finance_history(Request $request){

        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;

        $filter_income = $request->filter_income;
        $filter_payout = $request->filter_payout;
        $filter_fine = $request->filter_fine;
        $filter_coffee = $request->filter_coffee;

        /* Общая таблица */
        $all_logs = DB::table('employee_balance_logs')->where('employee_id', '=', $employee_id)->get();

        if(empty($filter_income)){

            $view_income = 'null';

        } else {

            $view_income = DB::table('employee_balance_logs')
            ->where('employee_id', '=', $employee_id)
            ->where('type', '=', 'Начисление')
            ->get();

        }
        //dd($view_income);

        if(empty($filter_payout)){

            $view_payout = 'null';

        } else {

            $view_payout = DB::table('employee_balance_logs')
            ->where('employee_id', '=', $employee_id)
            ->where('type', '=', 'Выплата')
            ->get();

        }

        if(empty($filter_fine)){

            $view_fine = 'null';

        } else {

            
            $view_fine = DB::table('employee_balance_logs')
            ->where('employee_id', '=', $employee_id)
            ->where('type', '=', 'Штраф')
            ->get();

        }
        if(empty($filter_coffee)){

            $view_coffee = 'null';

        } else {

            $view_coffee = DB::table('employee_balance_logs')
            ->where('employee_id', '=', $employee_id)
            ->where('type', '=', 'Кофе')
            ->get();
        }

        return view('employee.finance_history',
        [
            'view_fine' => $view_fine,
            'view_coffee' => $view_coffee,
            'view_payout' =>  $view_payout,
            'view_income' =>  $view_income,
            'all_logs' => $all_logs
        ]);
    }

    public function finance_history_filter(Request $request){

        // $filter_income = $request->filter_income;
        // $filter_payout = $request->filter_payout;
        // $filter_fine = $request->filter_fine;
        // $filter_coffee = $request->filter_coffee;

        // $employee_id = Auth::user()->id;


        // if(empty($filter_income)){

        //     $view_income = DB::table('employee_balance_logs')
        //     ->where('employee_id', '=', $employee_id)
        //     ->where('type', '=', 'Начисление')
        //     ->get();

        // } else {

        // }

        // if(empty($filter_payout)){

        //     $view_payout = DB::table('employee_balance_logs')
        //     ->where('employee_id', '=', $employee_id)
        //     ->where('type', '=', 'Выплата')
        //     ->get();

        // } else {

        // }

        // if(empty($filter_fine)){

        //     $view_fine = DB::table('employee_balance_logs')
        //     ->where('employee_id', '=', $employee_id)
        //     ->where('type', '=', 'Штраф')
        //     ->get();

        // } else {

        // }
        // if(empty($filter_coffee)){

        //     $view_coffee = DB::table('employee_balance_logs')
        //     ->where('employee_id', '=', $employee_id)
        //     ->where('type', '=', 'Кофе')
        //     ->get();

        // } else {

        // }
        //return back();

        return view('employee.finance_history',
        [
            // 'view_fine' => $view_fine,
            // 'view_coffee' => $view_coffee,
            // 'view_payout' =>  $view_payout,
            // 'view_income' =>  $view_income,
            // 'all_logs' => $all_logs
        ]);
    }


    /***** НАРЯДЫ *****/

    /* Показать наряды сотрудника */
    public function my_assignments(){
        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;
        
        // ... !!! ID пользователя и ID сотрудника отличаются
        // Получить employee_id
        

        /* Получаем информацию о нарядах */
        $assignments_data =
            DB::table('assignments')
                ->where(
                    [
                        ['responsible_employee_id', '=', $employee_id],
                    ]
                )
                ->get();
        
        return view('employee.assignments_index', ['assignments' => $assignments_data]);
    }

    public function my_complete_assignments(){
        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;
        
        // ... !!! ID пользователя и ID сотрудника отличаются
        // Получить employee_id
        

        /* Получаем информацию о нарядах */
        $assignments_data =
            DB::table('assignments')
                ->where(
                    [
                        ['responsible_employee_id', '=', $employee_id],
                        ['status', '=', 'complete']
                    ]
                )
                ->get();
        
        
        return view('employee.my_completed_assignments', ['assignments' => $assignments_data]);
    }

    public function my_uncomplete_assignments(){
        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;
        
        // ... !!! ID пользователя и ID сотрудника отличаются
        // Получить employee_id
        

        /* Получаем информацию о нарядах */
        $assignments_data =
            DB::table('assignments')
                ->where(
                    [
                        ['responsible_employee_id', '=', $employee_id],
                        ['status', '=', 'active']
                    ]
                )
                ->get();
        
        
        
        return view('employee.my_uncompleted_assignments', ['assignments' => $assignments_data]);
    }

    /* Один наряд сотрудника : управление */
    public function manage_assignment($assignment_id){
        /* Получаем наряд */
        $assignment = Assignment::find($assignment_id); 
        
        // .. Собираем информацию по наряду
        
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::where('assignment_id', $assignment_id)->get();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::where('assignment_id', $assignment_id)->get();
        /* Получаем выполненые работы */
        $assignment_work = Assignments_completed_works::where('assignment_id', $assignment_id)->get();
        
        
        // .. Собираем историю по наряду
        
        /* Возвращаем страницу */
        return view('employee.assignment', 
        [
            'assignment' => $assignment, 
            'assignment_income' => $assignment_income, 
            'assignment_expense' => $assignment_expense, 
            'assignment_work' => $assignment_work
        ]);
    }

    /* Пометить наряд выполнено */
    public function assignment_complete($assignment_id){

        $complete = 'complete';

        $complete_assignment = Assignment::find($assignment_id);
        $complete_assignment->status = $complete;
        $complete_assignment->save();

        /* Возвращаемся обратно на страницу нарядов */
        return back();

    }

    /* Пометить наряд невыполнено */
    public function assignment_uncomplete($assignment_id){

        $uncomplete = 'active';

        $uncomplete_assignment = Assignment::find($assignment_id);
        $uncomplete_assignment->status = $uncomplete;
        $uncomplete_assignment->save();

        /* Возвращаемся обратно на страницу нарядов */
        return back();

    }

    /* Пометить наряд "архив" */
    public function assignment_archive($assignment_id){

        $uncomplete = 'archive';

        $uncomplete_assignment = Assignment::find($assignment_id);
        $uncomplete_assignment->status = $uncomplete;
        $uncomplete_assignment->save();

        /* Возвращаемся обратно на страницу нарядов */
        return back();

    }
    

    /* Добавить заход денег : POST */
    public function add_income_post(Request $request){
        /* Создаём новое вхождение по заходу денег и вносим туда информацию */
        $new_income_entry = new Assignments_income();
        $new_income_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_income_entry->amount = $request->amount; /* Сумма захода */
        $new_income_entry->currency = $request->currency; /* Валюта захода */
        $new_income_entry->basis = $request->basis; /* Основание для захода денег */
        $new_income_entry->description = $request->description; /* Описание для захода */
        $new_income_entry->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_income_notification == 1){

            $text = "У вас новый заход денег!\n"
            . "<b>Сумма: </b>\n"
            . "$new_income_entry->amount\n"
            . "<b>Основание: </b>\n"
            . " $new_income_entry->basis\n"
            . "<b>Описание: </b>\n"
            .  $new_income_entry->description;

            Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
            ]);

        } else {

        }

        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить расход денег : POST */
    public function add_expense_post(Request $request){
        /* Создаём новое вхождение по расходу денег и вносим туда информацию */
        $new_expense_entry = new Assignments_expense();
        $new_expense_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_expense_entry->amount = $request->amount; /* Сумма расхода */
        $new_expense_entry->currency = $request->currency; /* Валюта расхода */
        $new_expense_entry->basis = $request->basis; /* Основание для расхода денег */
        $new_expense_entry->description = $request->description; /* Описание для расхода */
        $new_expense_entry->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_expense_notification == 1){

        $text = "У вас новый расход денег!\n"
        . "<b>Сумма: </b>\n"
        . "$new_expense_entry->amount\n"
        . "<b>Основание: </b>\n"
        . "$new_expense_entry->basis\n"
        . "<b>Описание: </b>\n"
        .  $new_expense_entry->description;

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        } else {


        }


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить выполненые работы : POST */
    public function add_works_post(Request $request){
        /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
        $new_works_entry = new Assignments_completed_works();
        $new_works_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_works_entry->basis = $request->basis; 
        $new_works_entry->description = $request->description; 
        $new_works_entry->status = 'unconfirmed';
        $new_works_entry->save();


        $text = "У вас выполненая работа!\n"
        . "<b>Основание: </b>\n"
        . "$new_works_entry->basis\n"
        . "<b>Описание: </b>\n"
        .  $new_works_entry->description;

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        /* Возвращаемся обратно на страницу наряда */
        return back();
    }

    /* Архив нарядов сотрудника */
    public function my_assignments_archive(){

        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;

        // Получить employee_id
        

        /* Получаем информацию о архивных(готовых) нарядах */
        $assignments_archive =
            DB::table('assignments')
                ->where(
                    [
                        ['responsible_employee_id', '=', $employee_id],
                        ['status', '=', 'archive']
                    ]
                )
                ->get();
        
        return view('employee.assignments_archive',
        [
            'assignments_archive' => $assignments_archive
        ]);
    }

    /**** Смены ****/

    /* Главная страница управления сменами */
    public function shifts_index(){
        // Получить сегодняшнюю смену
        // ...
        $today = date('Y-m-d');
        $today_shift = Shift::where('date', $today);
        

        return view('employee.shifts.shifts_index',
            [
                'today_shift' => $today_shift
            ]);
    }

    /* Открыть смену */
    public function start_shift(){
        // Получить ID сотрудника
        $employee_user_id = Auth::user()->id;

        // Проверить, открыта ли смена
        // ...

        // Если смена не открыта - открыть новую смену
        $new_shift = new Shift();
        $new_shift->new_shift($employee_user_id);

        // Вернуться на страницу управления сменами
        return back();

    }

    /* Закрыть смену */
    public function end_shift(Request $request){
        $shift_id = $request->shift_id;

        // ... Проверка на право закрыть
        // ... Проверка на статус смены (открыта ли?)

        // Добавление денег во время закрытия смены

        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;

        $shift_wage = $employee->standard_shift_wage;

        $employee_balance = $employee->balance;

        $apply_shift = $employee_balance + $shift_wage;

        DB::table('employees')
            ->where('id', '=', $employee_id)
            ->update(['balance' => $apply_shift]);

        // Добавить выплату за смену в общие логи
        $employee_balance_log = new Employee_balance_logs;
        $employee_balance_log->amount = $shift_wage;
        $employee_balance_log->reason = 'Выплата за смену';
        $employee_balance_log->action = 'deposit';
        $employee_balance_log->source = 'auto';
        $employee_balance_log->type = 'Выплата';
        $employee_balance_log->old_balance = $employee->balance;
        $employee_balance_log->date = date('Y-m-d');
        $employee_balance_log->employee_id = $employee_id;
        $employee_balance_log->save();

        // Закрываем смену
        $shift = Shift::find($shift_id);
        $shift->closed_at = date('H:i:s');
        $shift->status = 'closed';
        $shift->payment_amount = $shift_wage;
        $shift->payment_status = 'payed';
        $shift->save();

        // И возвращаемся на страницу смен
        return back();

    }
    
    
    /**** Заказы ****/
    
    /* Страница со списком заказов */
    public function employee_orders_index(){

        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'worker')->where('creator_id', Auth::user()->id)->get();
        
        /* Собираем дополнительные данные */
        foreach($supply_orders as $supply_order){
            /* Имя заказчика */
            $supply_order->creator_name = $supply_order->get_creator_name();
            /* Дата создания в виде ДД.ММ.ГГГГ */
            $supply_order->date_of_creation = $supply_order->get_creation_date();
            /* Количество позиций */
            $supply_order->entries_count = $supply_order->get_entries_count();
            /* Общее кол-во единиц*/
            $supply_order->items_count = $supply_order->get_items_count();
            /*Товар по данному заказу*/ 
            $supply_order->items = $supply_order->get_order_items();
                 
        }
             
        /* Возвращаем представление с данными */
        return view('employee.orders.employee_orders_index',
            [
                'supply_orders' => $supply_orders,                
            ]);
    }
    
    /* Новый ордер : страница */
    public function employee_order_new(){
        return view('employee.orders.employee_order_new');
    }
    
    /* Новый ордер : POST */
    public function employee_order_new_post(Request $request){
        
        /* Вносим заказ в базу */
        $new_order = new Supply_order();
        $new_order->creator_id = Auth::user()->id; // Создатель заказа
        $new_order->order_comment = $request->order_comment; // комментарий к заказу
        $new_order->status = 'worker';
        $new_order->save();
        
        /* Вносим предметы из заказа в базу */
        $counter = intval($request->entries_count);
        for($i = 1; $i <= $counter; $i++){
            // Получает данные из POST запроса
            $item_input_name = 'item'.$i;
            $item_count_name = 'count'.$i;
            $item_urgency_name = 'urgency'.$i;
            $item_name = $request->$item_input_name;
            $item_count = $request->$item_count_name;
            $item_urgency = $request->$item_urgency_name;

            // Внести в базу
            $new_order_item = new Supply_order_item();
            $new_order_item->supply_order_id = $new_order->id;
            $new_order_item->item = $item_name;
            $new_order_item->number = $item_count;
            $new_order_item->urgency = $item_urgency;
            
            $new_order_item->save();
            
        }

         /* Проверка оповещенияй (включено ли) */
         $user_id = Auth::user()->id;
         $user_name = Auth::user()->name;
         $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();
 
         if($notification_check->tg_supply_order_notification == 1){
            /* Нужно переделать под каждый товар (щас для одного твоара в заказе приходит оповещение) */
             /* Оповещения для телеграма */
             $text = "У вас новый заказ!\n"
             . "<b>Заказчик: </b>\n"
             . "$user_name\n"
             . "<b>Комментарий: </b>\n"
             . "$new_order->order_comment\n"
             . "<b>Название товара: </b>\n"
             . "$item_name\n"
             . "<b>Кол-во: </b>\n"
             . "$item_count\n"
             . "<b>Дата: </b>\n"
             . "$new_order->created_at\n"
             . "<b>Срочность: </b>\n" 
             .  $item_urgency;
 
             Telegram::sendMessage([
             'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
             'parse_mode' => 'HTML',
             'text' => $text
             ]);
 
         } else {
 
         }

        /* Вносим в лог запись о том, что заказ создан*/
        // ...

        // ... Сделать редирект на страницу индекс с заказами
        return redirect('/employee/orders/index');
    }
    
    /* Редактирование заказа : страница*/
    public function employee_order_edit($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);
        $supply_order->items = $supply_order->get_order_items();
        
        return view('employee.orders.employee_order_edit', 
                [
                    'supply_order' => $supply_order,                    
                ]);
    }
    
    /* Редактирование заказа : POST*/
    public function employee_order_edit_post(Request $request, $supply_order_id){
        /* Вносим измененный  заказ в базу */
        $edit_order = Supply_order::find($supply_order_id);        
        $edit_order->order_comment = $request->order_comment; // комментарий к заказу
        $edit_order->status = 'worker';
        $edit_order->save();
        
        /* Вносим измененные предметы из заказа в базу */
        $items = Supply_order_item::where('supply_order_id', $supply_order_id)->get();
        $i = 1;
        foreach ($items as $item){
            $item_input_name = 'item'.$i;
            $item_count_name = 'count'.$i;
            $item_urgency_name = 'urgency'.$i; 
            $item_name = $request->$item_input_name;
            $item_count = $request->$item_count_name; 
            $item_urgency = $request->$item_urgency_name; 
            
            $item->item = $item_name;
            $item->number = $item_count;
            $item->urgency = $item_urgency;
            
            $item->save();
            $i++;
        }

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_supply_order_notification == 1){
           /* Нужно переделать под каждый товар (щас для одного твоара в заказе приходит оповещение) */
            /* Оповещения для телеграма */
            $text = "У вас изменение заказа!\n"
            . "<b>Заказчик: </b>\n"
            . "$user_name\n"
            . "<b>Комментарий: </b>\n"
            . "$edit_order->order_comment\n"
            . "<b>Название товара: </b>\n"
            . "$item_name\n"
            . "<b>Кол-во: </b>\n"
            . "$item_count\n"
            . "<b>Дата: </b>\n"
            . "$edit_order->created_at\n"
            . "<b>Срочность: </b>\n" 
            .  $item_urgency;

            Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
            ]);

        } else {

        }
        
        
       
        return redirect('/employee/orders/index');
    }
    
    /* Подтвержденные заказы */
    public function employee_orders_active_index(){

        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'active')->where('creator_id', Auth::user()->id)->get();
        
        /* Собираем дополнительные данные */
        foreach($supply_orders as $supply_order){
            /* Имя заказчика */
            $supply_order->creator_name = $supply_order->get_creator_name();
            /* Дата создания в виде ДД.ММ.ГГГГ */
            $supply_order->date_of_creation = $supply_order->get_creation_date();
            /* Количество позиций */
            $supply_order->entries_count = $supply_order->get_entries_count();
            /* Общее кол-во единиц*/
            $supply_order->items_count = $supply_order->get_items_count();
            /*Товар по данному заказу*/ 
            $supply_order->items = $supply_order->get_order_items();
                 
        }
             
        /* Возвращаем представление с данными */
        return view('employee.orders.employee_orders_active_index',
            [
                'supply_orders' => $supply_orders,                
            ]);
    }
    
    public function employee_orders_completed_index(){

        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'completed')->where('creator_id', Auth::user()->id)->get();
        
        /* Собираем дополнительные данные */
        foreach($supply_orders as $supply_order){
            /* Имя заказчика */
            $supply_order->creator_name = $supply_order->get_creator_name();
            /* Дата создания в виде ДД.ММ.ГГГГ */
            $supply_order->date_of_creation = $supply_order->get_creation_date();
            /* Количество позиций */
            $supply_order->entries_count = $supply_order->get_entries_count();
            /* Общее кол-во единиц*/
            $supply_order->items_count = $supply_order->get_items_count();
            /*Товар по данному заказу*/ 
            $supply_order->items = $supply_order->get_order_items();
                 
        }
             
        /* Возвращаем представление с данными */
        return view('employee.orders.employee_orders_completed_index',
            [
                'supply_orders' => $supply_orders,                
            ]);
    }
}
