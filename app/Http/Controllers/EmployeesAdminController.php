<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use App\User;
use App\Employee;
use App\Employee_fine;
use App\Employee_balance_log;
use App\Coffee_token_log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Employees_notes;
use App\User_options;



class EmployeesAdminController extends Controller
{
    /* Список всех сотрудников */
    public function view_employees(){
        $employee_data = Employee::where('status', 'active')->get();


        return view('employees_admin.employees_admin_index', ['employee_data' => $employee_data]);

    }

    /* Страница добавления сотрудника */
    public function add_employee(){
        return view('employees_admin.add_employee');
    }

    /* Обработка POST запроса добавления сотрудника */
    public function add_employee_post(Request $request){
        /* Создать аккаунт под сотрудника */
        $login = $request->login;
        $password = $request->password;
        $new_user = new User();
        $new_user->name = $login;
        $new_user->password = Hash::make($password);
        $new_user->email = $login.'@test.com';
        $new_user->role = 'employee';
        $new_user->general_name = $request->name.' '.$request->surname;
        $new_user->save();
        $new_user_id = $new_user->id;

        /* Создать нового сотрудника */
        $new_employee = new Employee();
        $new_employee->general_name = $request->name.' '.$request->surname;
        $new_employee->status = 'active';
         /* Добавляем в таблицу работников ID соответствующего юзера */
        $new_employee->user_id = $new_user_id;
        $new_employee->balance = 0;
        $new_employee->save();

        /* Добавить ему нулевой баланс */
        $new_employee_id = $new_employee->id;        
        $new_employee_balance = new Employee_balance();
        $new_employee_balance->balance = 0;
        $new_employee_balance->employee_id = $new_employee_id;
        $new_employee_balance->save();

        /* - Добавление в логи создание сотрудника - */
        $create_employee_log = new Employees_logs();
        $create_employee_log_entry->employee_id = $employee_id; //id сотрудника 
        $create_employee_log_entry->author_id = $author_id;  //id автора заметки

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        $create_employee_log_entry->text = 'Создан новый сотрудник - ' .$employee_name. 'автор - '.$author_name. 'дата - ' .date('Y-m-d');  //текст о создании сотрудника(имя) и автор(имя), дата(date)
        $create_employee_log_entry->save();


        /* Вернуться ко списку сотрудников */
        return redirect()->route('view_employees');
    }

    /* Страница статусов сотрудника */
    public function manage_employee_status($employee_id){
        $employee = Employee::find($employee_id);

        return view('employees_admin.manage_employee_status', ['employee' => $employee]);
    }

    /* Действие перевода сотрудника в архив */
    public function archive_employee(Request $request){
        /* Перевести сотрудника в архив */

        $employee = Employee::find($request->employee_id);
        $employee->status = 'archived';
        $employee->save();

        /* - Добавление в логи о переводе сотрудника в архив - */
        $archive_employee_log = new Employees_logs();
        $archive_employee_log_entry->employee_id = $employee_id;  //id сотрудника
        $archive_employee_log_entry->author_id = $author_id;  //id автора 

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        $archive_employee_log_entry->text = 'Перевод в архив сотрудника - ' .$employee_name. 'автор - '.$author_name. 'дата - '.date('Y-m-d');  //текст лога архивации сотрудника (имя) и автор(имя), дата(date)
        $archive_employee_log_entry->save();       
        
        /* Вернуться ко списку сотрудников */
        return redirect()->route('view_employees');
    }

    public function employee_edit($employee_id){

        //$employee = Employee::find($employee_id)->first();
        $employee = Employee::find($employee_id);

        $employee_edit = DB::table('employees')->where('id', $employee_id)->first();

        return view('employees_admin.employee_edit_admin',
        [
            'employee_edit' => $employee_edit,
            'employee' => $employee

        ]);
    }

    public function apply_employee_edit(request $request){

        $employee_id = $request->id;

        $date_join = $request->date_join;
        $fio = $request->fio;
        $birthday = $request->birthday;
        $passport = $request->passport;
        $reserve_phone = $request->reserve_phone;
        $phone = $request->phone;
        $hour_from = $request->hour_from ;
        $hour_to = $request->hour_to;
        $fixed_charge = $request->fixed_charge;
        $pay_per_shift = $request->pay_per_shift;
        $telegram_id = $request->telegram_id;

        $employee = Employee::find($employee_id);
        $employee->date_join = $date_join;
        $employee->fio = $fio;
        $employee->birthday = $birthday;
        $employee->passport =  $passport;
        $employee->reserve_phone = $reserve_phone;
        $employee->phone = $phone;
        $employee->hour_from = $hour_from;
        $employee->hour_to = $hour_to;
        $employee->fixed_charge = $fixed_charge;
        $employee->pay_per_shift = $pay_per_shift;
        $employee->telegram_id = $telegram_id;
        $employee->save();

        if(!empty($request->document)){
            $request->scan_doc>store('public/doc_scan/'.$employee_id);
        }

        /* Возвращаемся на страницу */

        return back();
    }


    /* Общая страница финансов по работнику */
    public function employee_finances($employee_id){
        $employee = Employee::find($employee_id);

        $employee_fines = DB::table('employee_fines')->where('employee_id', '=', $employee_id)->get();

        $token_logs = Coffee_token_log::where('employee_id', $employee_id)->get();

        /* Получаем Начислеения */
        $balance_logs = Employee_balance_log::where(
            [
                ['employee_id', $employee_id],
                ['action', '=', 'deposit']
            ])->orderBy('created_at', 'desc')->get();

        /* Получаем Выплаты */
        $payout_logs = Employee_balance_log::where( 
            [

                ['employee_id', $employee_id],
                ['action', '=', 'withdrawal'],
                ['reason', '=', 'Выплата']

            ])->orderBy('created_at', 'desc')->get();

        return view('employees_admin.employee_finances_admin',
        [
             'employee' => $employee,
             'employee_fines' => $employee_fines,
             'token_logs' => $token_logs,
             'balance_logs' => $balance_logs,
             'payout_logs' => $payout_logs

        ]);
    }

    /* - Добавления примечания к сотруднику: страница - */
    public function add_note_to_employee_page($employee_id){
        $employee = Employee::find($employee_id);
        return view('employees_admin.add_note_to_employee', ['employee' => $employee]);
    }

    /* - Добавление примечания к сотруднику: POST - */
    public function add_note_to_employee_post(Request $request){
        //Добавить примечание
        $employee = Employee::find($request->employee_id);
        $new_employee_note_entry = new Employees_notes();
        $new_employee_note_entry->employee_id = $employee->id;
        $new_employee_note_entry->author_id = Auth::user()->id;
        $new_employee_note_entry->text = $request->note_content;
        $new_employee_note_entry->type = 'note';
        $new_employee_note_entry->save();

        /* - Добавление в логи создания заметки по сотруднику - */
        $create_employee_note_log = new Employees_notes_logs();
        $create_employee_note_log_entry->employee_id = $employee_id;  //id сотрудника 
        $create_employee_note_log_entry->author_id = $author_id;  //id автора заметки

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        $create_employee_note_log_entry->text = 'Создана заметка по сотруднику - ' .$employee_name. 'автор - '.$author_name. 'дата - '.date('Y-m-d'); //текст о создании заметки по клиенту(имя) и автор(имя), дата(date)
        $create_employee_note_log_entry->save();



        //Возврат на страницу сотрудника
        return redirect ('/admin/employee/' .$employee->id);
    }


    /* -- Редактирование примечания к сотруднику : страница -- */
    public function edit_employee_note($note_id){

        $employee_note = Employees_notes::find($note_id);
        $employee_id = $employee_note->employee_id;

        return view('employees_admin.edit_employee_note', [
            'note_id' => $note_id,
            'employee_id' => $employee_id,
            'employee_note' => $employee_note
        ]);
    }

    /* -- Редактирование примечания к сотруднику : POST --*/
    public function edit_employee_note_post(Request $request){
        $employee_note_entry = Employees_notes::find($request->note_id);
        $employee_note_entry->text = $request->text;
        $employee_note_entry->save();

        /* - Добавление в логи редактирование заметки по сотруднику - */
        $edit_employee_note_log = new Employees_notes_logs();
        $edit_employee_note_log_entry->employee_id = $employee_id;  //id сотрудника
        $edit_employee_note_log_entry->author_id = $author_id;  //id автора

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;       

         $edit_employee_note_log_entry->text = 'Редактирование заметки о сотруднике - ' .$employee_name. 'автор - '.$author_id. 'дата - '.date('Y-m-d');  //текст лога о редактировании заметки по клиенту(имя) и автор(имя), дата(date)
         $edit_employee_note_log_entry->save();     

        return redirect('/admin/employee/' .$employee_note_entry->employee_id);
    }

    /* - Удление примечания к сотруднику - */
    public function delete_employee_note($note_id){
        Employees_notes::find($note_id)->delete();

        /* - Добавление в логи удаление заметки о клиенте - */
        $delete_employee_note_log = new Employees_notes_logs();
        $delete_employee_note_log_entry->employee_id = $employee_id;  //id сотрудника 
        $delete_employee_note_log_entry->author_id = $author_id;  //id автора

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name; 
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        $delete_employee_note_log_entry->text = 'Удаление заметки о сотруднике - ' .$employee_name. 'автор - '.$author_name. 'дата - '.date('Y-m-d');  //текст лога удаления заметки по сотруднику(имя) автор(имя), дата(date)
        $delete_employee_note_log_entry->save();

        return back();
    }

    /* Изменение ставки сотрудника */
    public function change_standard_shift_wage(Request $request){
        // Задаём новую ставку
        Employee::find($request->employee_id)->set_new_wage($request->new_wage);

        /* - Добавление в логи изменение ставки по сотруднику - */
        $change_employee_shift_wage_log = new Employees_logs();
        $change_employee_shift_wage_log_entry->employee_id = $employee_id;  //id сотрудника
        $change_employee_shift_wage_log_entry->author_id = $author_id;  //id автора 

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;        

        $change_employee_shift_wage_log_entry->text = 'Изменение ставки сотрудника - ' .$employee_name. 'автор - ' .$author_name. 'дата - ' .date('Y-m-d');  //текст лога о изменении ставки сотрудника(имя) и автор(имя), дата(date)

        $change_employee_shift_wage_log_entry->save();
        

        // Возвращаемся на предыдущую страницу
        return back();
    }
    /* - Страница добавления примечания к сотруднику - */
    public function single_employee_notes($employee_id){
        $employee = Employee::find($employee_id);


        $employee_notes = DB::table('employees_notes')->where('employee_id', $employee->id)->get();


        foreach($employee_notes as $employee_note){
            $employee_note->author_name = User::find($employee_note->author_id)->general_name;

        }

        return view('employees_admin.single_employee_notes', [
            'employee' => $employee,
            'employee_notes' => $employee_notes,
            ]);
    }





    // Страница добавления документов сотрудника
    public function add_documents($employee_id){
        $employee = Employee::find($employee_id);

        return view('employees_admin.add_documents', ['employee'=>$employee]);
    }

    // Хранение файла документа POST
    public function add_documents_post(Request $request){
        $employee_id = $request->employee_id;
        $employee = Employee::find($employee_id);

        $request->scan->store('public/employee/'.$employee_id);

        return redirect('/documents/'.$request->employee_id);
    }

    // Страница сотрудника его документами
    public function show_employee_documents($employee_id) {
        $employee = Employee::find($employee_id);
        $images = [];
        foreach(Storage::files('public/employee/'.$employee_id) as $file){
             $images[] = $file;
        }

        return view('employees_admin.employee_documents', compact('employee', 'images'));
    }
    // Страница удаления документов
    public function documents_delete($employee_id){
        // Получаем список сканов паспорта сотрудника
        $images = [];
        foreach(Storage::files('public/employee/'.$employee_id) as $file){
             $images[] = $file;
        }
        return view('employees_admin.documents_delete', compact('employee_id', 'images'));
    }
    // Удаление документов : POST
    public function documents_delete_post(Request $request){
        /* Удалить документ */
        Storage::delete($request->path_to_file);

        /* Вернуться на страницу удаления документов */
        return redirect('documents_delete/'.$request->employee_id);
    }

    /*
    ********** Блок начислений (credit) **********
    */

    public function employee_credit_page($employee_id){
        $employee = Employee::find($employee_id);

        //данные о последних 10 операциях
        $balance = Employee_balance_log::where(
            [

                ['employee_id', $employee_id],
                ['action', '=', 'deposit']
            ])->orderBy('created_at', 'desc')->get();

        return view('employees_admin.employee_credit_page', ['employee' => $employee, 'balance' => $balance]);
    }

    public function add_employee_payment_manualy(Request $request){

        $request->validate([
            'balance' => 'required|numeric'
        ]);

        /* -----Добавить начисление ------ */
        $balance = Employee::find($request->employee_id);
        $balance_entry->balance = $request->balance;
        $balance_entry->date = date('Y-m-d');
        $balance_entry->save();

        return redirect('/supervisor/employee_finances/credit/{employee_id}', compact('balance'));


        // Добавить запись в общие логи
        $employee_balance = new Employee_balance_log;
        $employee_balance->amount = $amount;
        $employee_balance->action = 'dposit';
        $employee_balance->source = 'auto';
        $employee_balance->date = date('Y-m-d');
        $employee_balance->employee_id = $employee_id;
        $employee_balance->save();

        return back();
    }

    /* Начисления сотруднику : POST */
    public function employee_credit_page_post(Request $request){
        /* Начисления сотруднику в БД */
        $new_employee_credit = new Employee();
        $new_employee_credit->balance = $request->balance;
        $new_employee_credit->employee_id = $request->employee_id;
        $new_employee_credit->save();

        /*  */
        return response()->json(['result'=>'Начисления сотруднику произведены']);
    }

    /* Функция начисления сотруднику */
    public function add_balance(request $request){

        $employee_id = $request->employee_id;
        $employee = Employee::find($employee_id);
        /* Получаем employee id */

        $add_sum = $request->balance;
        $balance = $employee->balance;

        $new_balance = $balance + $add_sum;

        DB::table('employees')
            ->where('id', '=', $employee_id)
            ->update(['balance' => $new_balance]);


        /* Оповещения для телеграма */
        $text = "У вас новое начисление!\n"
        . "<b>Размер начисления: </b>\n"
        . "$add_sum\n"
        . "<b>Новый баланс: </b>\n"
        . "$new_balance";

       Telegram::sendMessage([
           'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
           'parse_mode' => 'HTML',
           'text' => $text
       ]);

        // Добавить начисление в общие логи
        $employee_balance_log = new Employee_balance_log;
        $employee_balance_log->amount = $add_sum;
        $employee_balance_log->action = 'deposit';
        $employee_balance_log->reason = 'Начисление';
        $employee_balance_log->date = date('Y-m-d');
        $employee_balance_log->employee_id = $employee_id;
        $employee_balance_log->old_balance = $balance;
        $employee_balance_log->save();

        /* Возвращаемся на страницу */

        return back();
    }

    /*
    ********** Блок выплат(payout) **********
    */
    /* Страница выплаты */
    public function employee_payout_page($employee_id){

        $employee = Employee::find($employee_id);

        /* Получаем выплаты по сотруднику */
        $employee_payout = Employee_balance_log::where(
            [

                ['employee_id', $employee_id],
                ['action', '=', 'withdrawal'],
                ['reason', '=', 'Выплата']

            ])->orderBy('created_at', 'desc')->get();

        return view('employees_admin.employee_payout_page',
        [
            'employee' => $employee,
            'employee_payout' => $employee_payout
        ]);
    }

    public function employee_payout(Request $request){

        $employee_id = $request->employee_id;
        $employee = Employee::find($employee_id);
        /* Получаем employee id */

        $add_payout = $request->payout_balance;
        $balance = $employee->balance;

        $new_balance = $balance - $add_payout;

        //$old_balance = $balance;

        DB::table('employees')
            ->where('id', '=', $employee_id)
            ->update(['balance' => $new_balance]);


        /* Оповещения для телеграма */
        $text = "У вас новая выплата!\n"
        . "<b>Размер выплаты: </b>\n"
        . "$add_payout\n"
        . "<b>Остаток(после выплаты): </b>\n"
        . "$new_balance";

       Telegram::sendMessage([
           'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
           'parse_mode' => 'HTML',
           'text' => $text
       ]);


        // Добавить запись в общие логи
        $employee_balance_log = new Employee_balance_log;
        $employee_balance_log->amount = -$add_payout;
        $employee_balance_log->action = 'withdrawal';
        $employee_balance_log->reason = 'Выплата';
        $employee_balance_log->date = date('Y-m-d');
        $employee_balance_log->employee_id = $employee_id;
        $employee_balance_log->old_balance = $balance;
        $employee_balance_log->save();

        /* Возвращаемся на страницу */

        return back();
    }

    /* История начислений */
    public function index() {
        $employee_balances = Employee::where('employee_balances',1)->orderBy('employee_id','balance')->take(10)->get();
        return view('employees_admin.employee_credit_page')->with([
            'employee_balances'=> $employee_balances
        ]);
    }

    /*
    ********** Блок со штрафами **********
    */

    /* Страница начисления штрафов */
    public function view_employee_fines($employee_id){
        $employee = Employee::find($employee_id);

        $fines =
            DB::table('employee_fines')
                ->where([
                        ['employee_id', '=', $employee_id], ['status', '=', 'pending']
                    ])
                ->get();

        return view('employees_admin.employee_fines_admin',
            [
                'employee' => $employee,
                'fines' => $fines
            ]
        );

    }

    /* Применить штраф */
    public function apply_fine($fine_id){
        $fine = Employee_fine::find($fine_id);
        // Перевести статус в применённые
        $fine->status = 'applied';
        $fine->save();

        // Вычесть из баланса сумму штрафа
        $employee_balance = DB::table('employees')
            ->where('id', '=', $fine->employee_id)
            ->first();

        $new_balance = $employee_balance->balance - $fine->amount;


        DB::table('employee_balances')
            ->where('employee_id', '=', $fine->employee_id)
            ->update(['balance' => $new_balance]); 

        DB::table('employees')
            ->where('id', '=', $fine->employee_id)
            ->update(['balance' => $new_balance]);



        // $employee_fine = DB::table('employee_fines')
        //     ->where('id', '=', $fine->employee_id)
        //     ->update(['old_balance' => $new_balance]);

        //$employee_fine= new Employee_fine;
        //$employee_fine->old_balance = $employee_balance->balance;
        // $employee_fine->save();

         /* Оповещения для телеграма */
         $text = "У вас новый штраф!\n"
         . "<b>Размер штрафа: </b>\n"
         . "$fine->amount\n"
         . "<b>Сумма с вычетом штрафа: </b>\n"
         . "$new_balance";

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
        ]);


        // Редирект на страницу штрафов сотрудника
        return redirect()->route('employee_fines', ['employee_id' => $fine->employee_id]);


    }

    /* Отменить штраф */
    public function quash_fine($fine_id){
        $fine = Employee_fine::find($fine_id);
        $fine->status = 'quashed';
        $fine->save();

        // Редирект
        return redirect()->route('employee_fines', ['employee_id' => $fine->employee_id]);
    }

    /* Добавить штраф вручную */

    public function add_fine_manually(Request $request){

        /* Найти айди рабочего */
        $employee_balance = DB::table('employees')
        ->where('id', '=', $request->employee_id)
        ->first();

        $emp_balance = $employee_balance->balance;

        /* Добавление штрафа в режиме "ожидает применения" */
        $new_fine = new Employee_fine;
        $new_fine->employee_id = $request->employee_id;
        $new_fine->amount = $request->fine_amount;
        $new_fine->reason = $request->fine_reason;
        $new_fine->old_balance = $emp_balance; // Добавления остатка
        $new_fine->status = 'pending';
        $new_fine->date = date('Y-m-d');
        $new_fine->save();

        /* - Добавляем в логи применение штрафа сотруднику - */
        $fine_to_employee_log = new Employees_logs();
        $fine_to_employee_log_entry->employee_id = $employee_id;  //id сотрудника
        $fine_to_employee_log_entry->author_id = $author_id;  //id автора

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        $fine_to_employee_log_entry->text = 'Штраф сотруднику - ' .$employee_name. 'был добавлен в ручную -' . $author_name .'дата - ' .date('Y-m-d');  //текст лога о добавлении штрафа сотруднику(имя) автором(имя), дата(date)

        $fine_to_employee_log_entry->save();


        /* Редирект на страницу штрафов */
        return redirect()->route('employee_fines', ['employee_id' => $request->employee_id]);
    }
    /*
    ********** Блок с жетонами кофе **********
    */
    /* Страница "жетоны кофе" */
    public function employee_coffee_token_index($employee_id){
        $employee = Employee::find($employee_id);
        // Получаем данные о последних 10 операциях выдачи жетонов по этому сотруднику
        $token_logs = Coffee_token_log::where('employee_id', $employee_id)->orderBy('created_at', 'desc')->get();
        return view('employees_admin.employee_coffee_tokens', ['employee' => $employee, 'token_logs' => $token_logs]);
    }

    /* Выдать жетон на кофе */
    public function employee_coffee_token_issue(Request $request){
        $request->validate([
            'token_count' => "required|numeric"
        ]);

        $employee_id = $request->employee_id;
        $token_count = $request->token_count;

        // Вычесть стоимость жетонов с баланса
        $token_price = 5; // Сделать подтягивание с базы
        $token_total = $token_price * $token_count;
        $employee_balance = DB::table('employees')->where('id', '=', $employee_id)->first();
        $new_employee_balance = $employee_balance->balance - $token_total;
        //$employee_balance->save();

        DB::table('employees')
        ->where('id', '=', $employee_id)
        ->update(['balance' => $new_employee_balance]);


        /* Найти айди рабочего */
        $employee_balance = DB::table('employees')
        ->where('id', '=', $request->employee_id)
        ->first();

        $emp_balance = $employee_balance->balance;

        // Добавить жетоны в историю
        $employee_coffee_log_entry = new Coffee_token_log;
        $employee_coffee_log_entry->token_count = $token_count;
        $employee_coffee_log_entry->date = date('Y-m-d');
        $employee_coffee_log_entry->employee_id = $employee_id;
        $employee_coffee_log_entry->old_balance = $emp_balance;
        $employee_coffee_log_entry->save();

        // Добавить запись в общие логи
        $employee_balance_log = new Employee_balance_log;
        $employee_balance_log->amount = $token_total;
        $employee_balance_log->reason = 'Списание за выдачу жетонов кофе';
        $employee_balance_log->action = 'withdrawal';
        $employee_balance_log->source = 'auto';
        $employee_balance_log->date = date('Y-m-d');
        $employee_balance_log->employee_id = $employee_id;
        $employee_balance_log->save();


        // Сделать редирект на страницу финансов пользователя
        return redirect()->route('employee_finances_admin', ['employee_id' => $employee_id]);


    }

    /*
    ********** Архив сотрудников **********
    */

    /* Отобразить архив сотрудников */
    public function show_employee_archive(){
        $archived_employees = Employee::where('status', 'archived')->get();

        return view('employees_admin.employee_archive', ['archived_employees' => $archived_employees]);
    }

    /*
    ********** Изменение прав доступа **********
    */

    /* Отобразить всех сотрудников */
    public function all_users(){
        $all_users = User::whereNotIn('role', ['client'])->get();

        return view('employees_admin.admin_all_users', ['all_users' => $all_users]);
    }

    public function change_access_rights($employee_id){
        $user = User::find($employee_id);


        return view('employees_admin.change_access_rights', ['user' => $user]);
    }

    public function change_access_rights_post(Request $request){
        $user = User::find($request->user_id);
        $user->role = $request->rights;
        $user->save();

        return redirect('/admin/all_users');
    }

    public function admin_shifts_index(){

        $shifts = DB::table('shifts')
        ->join('employees', 'shifts.employee_id', '=', 'employees.id')
        ->select(
            'shifts.*',
            'employees.general_name AS shift_emp_name'
        )
        ->get();

        return view('admin.shifts.admin_shifts_index',
        [
            'shifts' => $shifts
        ]);
    }
    /*
    ********** Настройка уведеомлений **********
    */
    public function admin_tg_notification_index(){

        $user_id = Auth::user()->id;

        $user_options = DB::table('user_options')->where('user_id', '=', $user_id)->first();

        return view('admin.notification.admin_tg_notification',
        [
            'user_options' => $user_options
        ]);
    }

    public function admin_tg_notification_update(Request $request){

        //$update_options = new User_options();
        $user_id = Auth::user()->id;

        $tg_assignment_notification = $request->tg_assignment_notification;
        $tg_income_notification = $request->tg_income_notification;
        $tg_expense_notification = $request->tg_expense_notification;

        if(empty($tg_assignment_notification)){

            $assignment_notification = 0;

        } else {

            $assignment_notification = 1;

        }
        if(empty($tg_income_notification)){

            $income_notification = 0;

        } else {

            $income_notification = 1;

        }
        if(empty($tg_expense_notification)){

            $expense_notification = 0;

        } else {

            $expense_notification = 1;

        }

        DB::table('user_options')
        ->where('user_id', '=', $user_id)
        ->update([
            'tg_assignment_notification' => $assignment_notification,
            'tg_income_notification' => $income_notification,
            'tg_expense_notification' => $expense_notification
        ]);

        //$update_options->save();
        //dd($request->all());
        return back();
    }

    public function employee_tg_notification_index(){

        $employee_id = Auth::user()->id;

        $employee_options = DB::table('user_options')->where('user_id', '=', $employee_id)->first();

        return view('admin.notification.employee_tg_notification',
        [
            'employee_options' => $employee_options
        ]);
    }

    public function employee_tg_notification_update(Request $request){

        return back();
    }

}
