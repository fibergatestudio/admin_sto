<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Telegram\Bot\Laravel\Facades\Telegram;

use App\Supply_order;
use App\Supply_order_item;
use App\Supply_order_log;

class Supply_orders_Admin_Controller extends Controller
{
    /* Главная страница со списком заказов */
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
            /*Товар по данному заказу*/
            $supply_order->items = $supply_order->get_order_items();

        }

        /* Возвращаем представление с данными */
        return view('admin.supply_orders.supply_orders_index',
            [
                'supply_orders' => $supply_orders,
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
        $new_order->order_comment = $request->order_comment; // комментарий к заказу
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

        /* Отправляем телеграм оповещение о созданном заказе*/

        $text = "У вас новый заказ!\n"
        . "<b>Название товара: </b>\n"
        . "$item_name\n"
        . "<b>Кол-во: </b>\n"
        . "$item_count\n"
        . "<b>Срочность: </b>\n"
        .  "$item_urgency\n"
        . "<b>Комментарий к заказу: </b>\n"
        .  $request->order_comment;

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        /* Вносим в лог запись о том, что заказ создан*/
        // ...

        // ... Сделать редирект на страницу индекс с заказами
        return redirect('/admin/supply_orders/index');
    }

    /* Управление заказом : страница */
    public function manage_supply_order($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);
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


        return view('admin.supply_orders.manage_supply_order',
                [
                    'supply_order' => $supply_order,
                ]);
    }

    /* Редактирование заказа : страница*/
    public function edit_supply_order($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);
        $supply_order->items = $supply_order->get_order_items();

        return view('admin.supply_orders.edit_supply_order',
                [
                    'supply_order' => $supply_order,
                ]);
    }

    /* Редактирование заказа : POST*/
    public function edit_supply_order_post(Request $request, $supply_order_id){
        /* Вносим измененный  заказ в базу */
        $edit_order = Supply_order::find($supply_order_id);
        $edit_order->order_comment = $request->order_comment; // комментарий к заказу
        $edit_order->status = 'active';
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

        // $inputcheck = $request->'item'+id.id;

        for($i = 1 ; $i <= $request->new_entries_count; $i++ ){
            $item_check = 'new_item'.$i;

            if(!empty($request->$item_check)){

                /* Вносим доп предметы из заказа в базу */
                $counter = intval($request->new_entries_count);
                for($i = 1; $i <= $counter; $i++){
                // Получает данные из POST запроса
                $new_item_input_name = 'new_item'.$i;
                $new_item_count_name = 'new_count'.$i;
                $new_item_urgency_name = 'new_urgency'.$i;
                $new_item_name = $request->$new_item_input_name;
                $new_item_count = $request->$new_item_count_name;
                $new_item_urgency = $request->$new_item_urgency_name;

                // Внести в базу
                $new_order_item = new Supply_order_item();
                $new_order_item->supply_order_id = $edit_order->id;
                $new_order_item->item = $new_item_name;
                $new_order_item->number = $new_item_count;
                $new_order_item->urgency = $new_item_urgency;

                $new_order_item->save();

                }
            } else {
                //Ничего не делаем
            }
        }

         /* Отправляем телеграм оповещение о редактировании заказа*/

         $text = "У вас изменения заказа!\n"
         . "<b>Название товара: </b>\n"
         . "$item_name\n"
         . "<b>Кол-во: </b>\n"
         . "$item_count\n"
         . "<b>Срочность: </b>\n"
         .  "$item_urgency\n"
         . "<b>Комментарий к заказу: </b>\n"
         .  $request->order_comment;

         Telegram::sendMessage([
             'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
             'parse_mode' => 'HTML',
             'text' => $text
         ]);

        return redirect('/admin/supply_orders/index');
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


         /* Отправляем телеграм оповещение о архивировании заказа*/

         $text = "Заказ Номер:$supply_order->id перемещен в архив !\n"
         . "<b>Комментарий к заказу: </b>\n"
         .  $supply_order->order_comment;

         Telegram::sendMessage([
             'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
             'parse_mode' => 'HTML',
             'text' => $text
         ]);


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
            /*Товар по данному заказу*/
            $supply_order->items = $supply_order->get_order_items();
        }

        return view('admin.supply_orders.archive_index', ['archived_orders' => $archived_orders]);
    }

    /* Удалить заказ из архива */
    public function delete_archived_order($order_id){

        // Удалить предметы
        Supply_order_item::where('supply_order_id', $order_id)->delete();

        // Удалить логи
        Supply_order_log::where('supply_order_id', $order_id)->delete();

        // Удалить заказ
        Supply_order::find($order_id)->delete();

        // Вернуться в архив
        return back();

    }

    /* Заказы требующие подтверждения (статус - worker) */
    public function supply_orders_worker_index(){

        /* Получаем из базы данные обо всех рабочих заказах на поставку */
        $supply_orders = Supply_order::where('status', 'worker')->get();

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
        return view('admin.supply_orders.supply_orders_worker_index',
            [
                'supply_orders' => $supply_orders,
            ]);
    }

    /*Подтверждение заказа (статус изменяется на - active )*/
    public function confirm_supply_order($supply_order_id){
        $supply_order = Supply_order::find($supply_order_id);
        $supply_order->status = 'active';
        $supply_order->save();

        /* Редирект на страницу заказов */
        return redirect('/admin/supply_orders/worker');
    }

}
