<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supply_order;
use App\Supply_order_item;
use DB;
use Auth;
use App\User;
use App\Account;
use App\AccountCategory;
use App\AccountOperation;
use App\AccountOperationCategory;
use App\AccountCategoryArchive;


class Supply_officer_Controller extends Controller
{
    
    /* Главная страница */
    public function index(){
        return view('supply_officer.index');
    }

    
    /*** Страница всех активных заказов на снабжение ***/
    public function all_orders(){
        /* Получаем из базы данные обо всех активных заказах на поставку */
        $supply_orders = Supply_order::where('status', 'confirmed')->get();
        
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

        $accounts = Account::all();
        $employees = DB::table('employees')->get();
        
        /* Возвращаем представление с данными */
        return view('supply_officer.all_orders',
            [
                'supply_orders' => $supply_orders,
                'accounts' => $accounts,
                'employees' => $employees
            ]);
    }

    
    /* Применить изменения ордера */
    public function apply_order_edit(Request $request){

        $order_id = $request->order_id;
        $payment_method = $request->payment_method;
        $order_price = $request->order_price;
        $given_to = $request->given_to;

        DB::table('supply_orders')
        ->where('id', '=', $order_id)
        ->update([
            'payment_method' => $payment_method,
            'order_price' => $order_price,
            'given_to' => $given_to
        ]);

        return back();
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
    /*public function order_completed_action(Request $request){
        // Получаем данные по заказу 
        $order_id = $request->order_id;

        // Меняем статус на "выполнено" 
        $order = Supply_order::find($order_id);
        $order->set_to_completed();
        $order->save();


        $payment_method = $request->payment_method;
        $remove_amount = $request->order_price;
        //Если оплата наличными - убрать снять со счета снабженца сумму
        if($payment_method = "Наличные"){

            //New Id
            $supply_user_id = $request->user_id;

            $user_wallet = DB::table('employees')->where('id', $supply_user_id)->first();

            //dd($supply_user_id);

            $wallet = $user_wallet->balance;

            $new_amount = $wallet - $remove_amount;

            //dd($new_amount);

            DB::table('employees')
            ->where('id', $supply_user_id)
            ->update([
                'balance' => $new_amount
            ]);

        }

        // Редирект на страницу выполненных заказов 
        return redirect('supply_officer/completed_orders');

    }*/


    /* Управление заказом : Выполнить */
    public function order_completed_action(Request $request){
        // Получаем данные по заказу 
        $order_id = $request->order_id;
        $sum = $request->sum;
        $account_currency = $request->account_currency;
        $sum_currency = $request->currency;
        // Получаем курс валют 
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        // Приводим сумму в соответствие с курсом
        if ($account_currency !== $sum_currency) {
            if ($account_currency === 'MDL' && $sum_currency === 'USD') {
                $sum = round($sum/$usd);
            }
            else if ($account_currency === 'MDL' && $sum_currency === 'EUR') {
                $sum = round($sum/$eur);
            }
            else if ($account_currency === 'USD' && $sum_currency === 'MDL') {
                $sum = round($sum*$usd);
            }
            else if ($account_currency === 'EUR' && $sum_currency === 'MDL') {
                $sum = round($sum*$eur);
            }
            else if ($account_currency === 'USD' && $sum_currency === 'EUR') {
                $temp = round($sum/$eur*100)/100;
                $sum = round($temp*$usd);
            }
            else if ($account_currency === 'EUR' && $sum_currency === 'USD') {
                $temp = round($sum/$usd*100)/100;
                $sum = round($temp*$eur);
            }
        }

        
        $account_operation = AccountOperation::where('account_id', $request->account_id)->latest()->first();
        $account = Account::find($request->account_id);
        // Создаем операцию со счетом
        $new_account_operation = new AccountOperation();
        $new_account_operation->account_id = $request->account_id;
        $new_account_operation->author = Auth::User()->name;
        $new_account_operation->tag = '';       
        $new_account_operation->comment = 'Покупка '.$request->name_quantity;
        $new_account_operation->date = date('H:i:s');
        
        $new_account_operation->expense = $sum;
        
        if ($account_operation->balance < $new_account_operation->expense) {
            return "Недостаточно средств !";
        }
        
        $new_account_operation->balance = $account_operation->balance - $new_account_operation->expense;
        $new_account_operation->category = 'Заказы на снабжение';
        $new_account_operation->save();
        $account->balance = $new_account_operation->balance;
        $account->save();

        // Меняем статус на "выполнено" 
        $order = Supply_order::find($order_id);
        $order->set_to_completed();
        $order->save();

        // Редирект на страницу выполненных заказов 
        return redirect('supply_officer/completed_orders');

    }


    /* Главная страница счетов */
    public function finances_index() 
    {       
        $users = User::all();
        $categories = AccountCategory::all();
        $accounts = Account::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        return view('supply_officer.finances_index', [
            'users' => $users, 
            'categories' => $categories, 
            'accounts' => $accounts,
            'usd' => $usd,
            'eur' => $eur
        ]);
    }


    // Добавление счета
    public function add_account(Request $request)
    {
        $new_account = new Account();
        $new_account->name = $request->name;
        $new_account->category_id = $request->category;
        $new_account->currency = $request->currency;
        $new_account->user_email = $request->user_email;
        $new_account->status = 'active';
        $new_account->save();
        $new_account_operation = new AccountOperation();
        $new_account_operation->account_id = $new_account->id;
        $new_account_operation->author = Auth::User()->name;
        $new_account_operation->comment = 'Открытие счета';
        $new_account_operation->date = date("Y-m-d H:i:s");
        $new_account_operation->save();
        return redirect('/supply_officer/finances/index');
    }

    // Просмотр счета
    public function show_account($account_id)
    {
        $users = User::all();
        $account = Account::where('id', $account_id)->first();
        $accounts = Account::where('status', 'active')->get();
        $categories = AccountCategory::all();
        $account_operations = AccountOperation::where('account_id', $account_id)->orderBy('created_at', 'DESC')->get();
        $account_operation_categories = AccountOperationCategory::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        
        return view('supply_officer.account_operations', [
            'account_operations' => $account_operations, 
            'categories' => $categories, 
            'users' => $users, 
            'account_operation_categories' => $account_operation_categories,
            'accounts' => $accounts,
            'account' => $account,
            'usd' => $usd,
            'eur' => $eur
        ]);
    }


    public function finances_archive(){

        $all_cats = AccountCategoryArchive::all();

        return view('supply_officer.finances_archive', compact('all_cats') );
    }

    public function delete_account($account_id){

        DB::table('accounts')
        ->where([
            'id' => $account_id,
        ])
        ->delete();

        return back();
    }


    // Добавление операции
    public function add_operation(Request $request, $account_id)
    {
        $account_operation = AccountOperation::where('account_id', $account_id)->latest()->first();
        $account = Account::find($account_id);

        $new_account_operation = new AccountOperation();
        $new_account_operation->account_id = $account_id;
        $new_account_operation->author = Auth::User()->name;
        $new_account_operation->tag = $request->tag;       
        $new_account_operation->comment = $request->comment;
        $new_account_operation->date = $request->date;
        if (isset($request->income)) {
            $new_account_operation->income = $request->income;
            $new_account_operation->balance = $account_operation->balance + $new_account_operation->income;
        }
        if (isset($request->expense)) {
            $new_account_operation->expense = $request->expense;
            if ($account_operation->balance < $new_account_operation->expense) {
                return "Недостаточно средств !";
            }
            $new_account_operation->balance = $account_operation->balance - $new_account_operation->expense;
        }
        if ($request->type_operation === 'Перевод на счет') {
            $to_account = Account::find($request->to_account_id);
            $new_account_operation->category = 'Перевод на счет: '.$to_account->name;
            $new_account_operation_to = new AccountOperation();
            $new_account_operation_to->account_id = $request->to_account_id;
            $new_account_operation_to->author = Auth::User()->name;
            $new_account_operation_to->category = 'Перевод со счета: '.$account->name;
            $new_account_operation_to->comment = $request->comment;
            $new_account_operation_to->date = $request->date;
            $new_account_operation_to->income = $request->income_to;
            $account_operation_to = AccountOperation::where('account_id', $request->to_account_id)->latest()->first();
            $new_account_operation_to->balance = $account_operation_to->balance + $new_account_operation_to->income;
            $new_account_operation_to->save();
            $to_account->balance = $new_account_operation_to->balance;
            $to_account->save();
        }
        else {
            $new_account_operation->category = $request->category;
        }
        $new_account_operation->save();
        $account->balance = $new_account_operation->balance;
        $account->save();

        $account_operation_category = AccountOperationCategory::where([
            ['type_operation','=', $request->type_operation],
            ['name','=', $request->category]
        ])->first();
        /*echo '<pre>'.print_r($account_operation_category,true).'</pre>';
        die();*/
        if (empty($account_operation_category) AND $request->type_operation !== 'Перевод на счет') {
            $new_account_operation_category = new AccountOperationCategory();
            $new_account_operation_category->type_operation = $request->type_operation;
            $new_account_operation_category->name = $request->category;
            $new_account_operation_category->save();
        }                   
        
        return back();
        //return redirect('/admin/finances/index');
    }
}
