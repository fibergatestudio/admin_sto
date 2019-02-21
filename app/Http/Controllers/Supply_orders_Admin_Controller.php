<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Supply_order;
use App\Supply_order_item;
use App\Supply_order_log;

class Supply_orders_Admin_Controller extends Controller
{
    /* Главная страница */
    public function supply_orders_index(){

        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'active')->get();
        
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
        }

        /* Возвращаем представление с данными */
        return view('admin.supply_orders.supply_orders_index',
            [
                'supply_orders' => $supply_orders
            ]);
    }

    /* Новый ордер : страница */
    public function new_supply_order(){
        return view('admin.supply_orders.new_supply_order');
    }

    /* Новый ордер : POST */
    public function new_supply_order_post(Request $request){
        
        /* Вносим заказ в базу */
        $new_order = new Supply_order();
        $new_order->creator_id = Auth::user()->id; // Создатель заказа
        $new_order->save();
        
        /* Вносим предметы из заказа в базу */
        $counter = intval($request->entries_count);
        for($i = 1; $i <= $counter; $i++){
            // Получает данные из POST запроса
            $item_input_name = 'item'.$i;
            $item_count_name = 'count'.$i;
            $item_name = $request->$item_input_name;
            $item_count = $request->$item_count_name;

            // Внести в базу
            $new_order_item = new Supply_order_item();
            $new_order_item->supply_order_id = $new_order->id;
            $new_order_item->item = $item_name;
            $new_order_item->number = $item_count;
            $new_order_item->save();
            
        }

        /* Вносим в лог запись о том, что заказ создан*/
        $new_order_log = new Supply_order_logs();
        $new_order_log_entry->supply_order_id = $supply_order_id;
        $new_order_log_entry->author_id = $author_id;

        /* - Имя автора - */
        $author = Users::find($responcible_supply_officier_id);
        $author_name = $author->general_name;
        /* - Номер заказа - */
        $order = Supply_orders::find($supply_order_id);
        $order_number = $order->id;

        $new_order_log_entry->log_entry_content = 'Создан заказ № '.$order_number.'сотрудником - '.$author_name. ' дата - ' .date('Y-m-d');  // текст лога о создании заказа(№) автор(имя) дата(date)
        $new_order_log_entry->save();

        // ... Сделать редирект на страницу индекс с заказами
        return redirect('/admin/supply_orders/index');
    }

    /* Управление заказом : страница */
    public function manage_supply_order($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);

        return view('admin.supply_orders.manage_supply_order', ['supply_order' => $supply_order]);
    }

    /* Архивировать заказ : действие */
    public function archive_supply_order($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);
        $supply_order->status = 'archived';
        $supply_order->save();

        /* - Вносим в логи архивирование заказа - */
        $new_supply_order_arhive_log = new Supply_order_logs();
        $new_supply_order_arhive_log_entry->order_id = $order_id;
        $new_supply_order_arhive_log_entry->author_id = $author_id;

        /* - Имя автора - */
        $author = Users::find($responcible_supply_officier_id);
        $author_name = $author->general_name;

        /* - Номер заказа - */
        $order = Supply_orders::find($supply_order_id);
        $order_number = $order->id;

        $new_supply_order_arhive_log_entry->text = 'Заказ номер - ' .$order_number. 'переведён в архив автором - ' .$author_name. 'дата - ' .date('Y-m-d'); //текст лога о переводе заказа(№) в архив автором(имя) дата(date)
        $new_supply_order_arhive_log_entry->save();
        
        /* Редирект на страницу архива */
        return redirect('/admin/supply_orders/archive');
    }
    
    /* Архив : просмотр */
    public function archive_index(){
        $archived_orders = Supply_order::where('status', 'archived')->get();

         /* Собираем дополнительные данные */
         foreach($archived_orders as $supply_order){
            /* Имя заказчика */
            $supply_order->creator_name = $supply_order->get_creator_name();
            /* Дата создания в виде ДД.ММ.ГГГГ */
            $supply_order->date_of_creation = $supply_order->get_creation_date();
            /* Количество позиций */
            $supply_order->entries_count = $supply_order->get_entries_count();
            /* Общее кол-во единиц*/
            $supply_order->items_count = $supply_order->get_items_count();
        }

        return view('admin.supply_orders.archive_index', ['archived_orders' => $archived_orders]);
    }

    /* Удалить заказ из архива */
    public function delete_archived_order($order_id){
        
        // Удалить предметы
        Supply_order_item::where('supply_order_id', $order_id)->delete();

        // Удалить логи
        Supply_order_log::where('supply_order_id', $order_id)->delete();

        /* - Добавление в логи удаление заказа из архива - */
        $delete_supply_order_arhive_log = new Supply_order_logs();
        $delete_supply_order_arhive_log_entry->order_id = $order_id;
        $delete_supply_order_arhive_log_entry->author_id = $author_id;

        /* - Имя автора - */
        $author = Users::find($responcible_supply_officier_id);
        $author_name = $author->general_name;

        /* - Номер заказа - */
        $order = Supply_orders::find($supply_order_id);
        $order_number = $order->id;

        $delete_supply_order_arhive_log_entry->text = 'Удалён заказ номер - ' .$order_number. 'автором - ' .$authors_name. 'дата - ' .date('Y-m-d');  // текст лога про удаление из архива заказа(номер) автором(имя), дата (date)
        $delete_supply_order_arhive_log_entry->save();

        // Удалить заказ
        Supply_order::find($order_id)->delete();
        
        // Вернуться в архив
        return back();

    }
}
