<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Employee;
use App\Employee_fine;
use App\Employee_balance;
use App\Employee_balance_log;
use App\Coffee_token_log;
use App\Employees_notes;



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
    
    /* Общая страница финансов по работнику */
    public function employee_finances($employee_id){
        $employee = Employee::find($employee_id);
        return view('employees_admin.employee_finances_admin', ['employee' => $employee]);
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
    /* - Страница добавления примечания к сотруднику */
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

        

    

    /*
    ********** Блок начислений (credit) **********
    */

    public function employee_credit_page($employee_id){
        $employee = Employee::find($employee_id);

        //данные о последних 10 операциях 
        $balance = Employee_balance_log::where('employee_id', $employee_id)->orderBy('created_at', 'desc')->get();

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
        $employee_balance = new Employee_balance;
        $employee_balance->amount = $amount;
        $employee_balance->action = 'dposit';
        $employee_balance->source = 'auto';
        $employee_balance->date = date('Y-m-d');
        $employee_balance->employee_id = $employee_id;
        $employee_balance->save();

        return back();
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
        $employee_balance = DB::table('employee_balances')
            ->where('employee_id', '=', $fine->employee_id)
            ->first();
        
        $new_balance = $employee_balance->balance - $fine->amount;

        DB::table('employee_balances')
            ->where('employee_id', '=', $fine->employee_id)
            ->update(['balance' => $new_balance]); 

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
        /* Добавление штрафа в режиме "ожидает применения" */
        $new_fine = new Employee_fine;
        $new_fine->employee_id = $request->employee_id;
        $new_fine->amount = $request->fine_amount;
        $new_fine->reason = $request->fine_reason;
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
        $employee_balance = Employee_balance::where('employee_id', $employee_id)->first();
        $employee_balance->balance = $employee_balance->balance - $token_total;
        $employee_balance->save();

        // Добавить жетоны в историю
        $employee_coffee_log_entry = new Coffee_token_log;
        $employee_coffee_log_entry->token_count = $token_count;
        $employee_coffee_log_entry->date = date('Y-m-d');
        $employee_coffee_log_entry->employee_id = $employee_id;
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
}
