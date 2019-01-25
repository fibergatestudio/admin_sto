<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Assignment;
use App\Shift;
use App\Supply_order;
use App\Supply_order_item;

class Employee_Dashboard_Controller extends Controller
{
    /* Главная страница для ЛК сотрудника */
    public function index(){
        return view('employee.employee_dashboard_index');
    }

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
                        ['status', '=', 'active']
                    ]
                )
                ->get();
        
        return view('employee.assignments_index', ['assignments' => $assignments_data]);
    }

    /* Один наряд сотрудника : управление */
    public function manage_assignment($assignment_id){
        $assignment = Assignment::find($assignment_id); // Получаем наряд
        // .. Собираем информацию по наряду
        // .. Собираем историю по наряду
        
        // ..
    }
    
    /* Архив нарядов сотрудника */
    public function my_assignments_archive(){
        // ...
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

        // Закрываем смену
        $shift = Shift::find($shift_id);
        $shift->closed_at = date('H:i:s');
        $shift->status = 'closed';
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
