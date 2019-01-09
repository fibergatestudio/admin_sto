<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Supply_order;
use App\Supply_order_item;

class Supply_officer_Controller extends Controller
{
    /* Главная страница */
    public function index(){
        return view('supply_officer.index');
    }

    /*** Страница всех активных заказов на снабжение ***/
    public function all_orders(){
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
        return view('supply_officer.all_orders',
            [
                'supply_orders' => $supply_orders
            ]);
    }

    /*** Выполненные заказы : список ***/
    public function completed_orders(){
        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'completed')->get();
        
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

            /* Дата завершения в виде ДД.ММ.ГГГГ */
            $supply_order->date_of_completion = $supply_order->get_completion_date();

            /* Ответственное лицо */
            $supply_order->responsible_officer_name = $supply_order->get_responsible_officer_name();
        }
        
        /* Возвращаем представление с данными */
        return view('supply_officer.completed_orders',
            [
                'supply_orders' => $supply_orders
            ]);
    }

    /*** Просмтор заказа по ID ***/
    public function view_order($order_id){
        /* Получаем данные по заказу */
        $supply_order = Supply_order::find($order_id);

        /* Получаем дополнительные данные по заказу */
        /* Имя заказчика */
        $supply_order->creator_name = $supply_order->get_creator_name();
        
        /* Дата создания в виде ДД.ММ.ГГГГ */
        $supply_order->date_of_creation = $supply_order->get_creation_date();

        /* Количество позиций */
        $supply_order->entries_count = $supply_order->get_entries_count();
        /* Общее кол-во единиц*/
        $supply_order->items_count = $supply_order->get_items_count();
        
        /* Получаем данные по предметам в заказе */
        $supply_order_items = Supply_order_item::where('supply_order_id', $order_id)->get();

        /* Возвращаем представление с нужными данными */
        return view('supply_officer.view_order',
            [
                'supply_order' => $supply_order,
                'supply_order_items' => $supply_order_items            
            ]); 
    }
    
    /*** Заказ выполнен : POST ***/
    public function order_completed_action(Request $request){
        /* Получаем данные по заказу */
        $order_id = $request->order_id;

        /* Меняем статус на "выполнено" */
        $order = Supply_order::find($order_id);
        $order->set_to_completed();
        $order->save();

        /* Редирект на страницу выполненных заказов */
        return redirect('supply_officer/completed_orders');

    }
}
