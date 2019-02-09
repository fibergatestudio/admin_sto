<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Employee;
use App\Employee_fine;
use App\Employee_balance;
use App\Employee_balance_log;
use App\Coffee_token_log;

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
        
        /* Вернуться к списку сотрудников */
        return redirect()->route('view_employees');
    }
    
    /* Общая страница финансов по работнику */
    public function employee_finances($employee_id){
        $employee = Employee::find($employee_id);
        
        return view('employees_admin.employee_finances_admin', ['employee' => $employee]);

    }

    /* Изменение ставки сотрудника */
    public function change_standard_shift_wage(Request $request){
        // Задаём новую ставку
        Employee::find($request->employee_id)->set_new_wage($request->new_wage);
        
        // Возвращаемся на предыдущую страницу
        return back();
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

        return view('employees_admin.employee_credit_page', ['employee' => $employee]);

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
}
