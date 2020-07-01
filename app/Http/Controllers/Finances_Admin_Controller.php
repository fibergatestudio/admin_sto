<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Account;
use App\AccountCategory;
use App\AccountOperation;
use App\AccountOperationCategory;
use App\AccountCategoryArchive;

class Finances_Admin_Controller extends Controller
{
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

        return view('admin.finances.finances_index', [
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
    	return redirect('/admin/finances/index');
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
        
        return view('admin.finances.account_operations', [
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

    public function apply_edit(Request $request){

        $account_id = $request->account_id;

        $name = $request->name;
        $category = $request->category_id;
        $currency = $request->currency;
        $user_email = $request->user_email;
        $balance = $request->balance;

        DB::table('accounts')
        ->where('id', '=', $account_id)
        ->update([
            'name' => $name,
            'user_email' => $user_email,
            'category_id' => $category,
            'currency' => $currency,
            'balance' => $balance
            ]);

        return back();
    }

    public function finances_archive_category($fin_cat_name){

        //dd($fin_cat_name);

        DB::table('account_categories')->where('name', $fin_cat_name)->delete();

        $archive_cat = DB::table('account_categories')->where('name', $fin_cat_name)->first();

        $new_arch = new AccountCategoryArchive();
        $new_arch->name = $fin_cat_name;
        $new_arch->save();

        //DB::table('account_categories_archive')->

        return back();
    }

    public function finances_archive(){

        $all_cats = AccountCategoryArchive::all();

        return view('admin.finances.finances_archive', compact('all_cats') );
    }

    public function delete_account($account_id){

        //dd($account_id);

        DB::table('accounts')
        ->where([
            'id' => $account_id,
        ])
        ->delete();

        return back();
    }


    public function archive_account($account_id){

        //$acc = DB::table('accounts')->where('id', $account_id)->first();

        DB::table('accounts')->where('id', $account_id)
        ->update([
            'status' => 'archived',
        ]);

        return back();
    }

    public function view_archive(){

        $archive_acc = DB::table('accounts')->where('status', 'archived')->get();

        

        return view('admin.finances.accounts_archive', compact('archive_acc') );
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
        if ($request->assignment_id) {
            $new_account_operation->assignment_id = $request->assignment_id;
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

    // Добавление категории
    public function add_category(Request $request)
    {
        $categories = AccountCategory::all();
        $i = 0;
        foreach ($categories as $category) {
            $category->name = $request->category_name[$i];
            $i++;
            $category->save();
        }

        if (isset($request->new_category_name)) {
            $new_category = new AccountCategory();
            $new_category->name = $request->new_category_name;
            $new_category->save();
        }

        return redirect('/admin/finances/index');
    }
}
