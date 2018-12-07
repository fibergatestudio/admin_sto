<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Employee;
use App\Employee_fine;
use App\Employee_balance;
use App\Employee_balance_log;
use App\Coffee_token_log;

class EmployeesAdminController extends Controller
{
    /* Список всех сотрудников */
    public function view_employees(){
        $employee_data = Employee::all();


        return view('employees_admin.employees_admin_index', ['employee_data' => $employee_data]);

    }
    
    /* Общая страница финансов по работнику */
    public function employee_finances($employee_id){
        $employee = Employee::find($employee_id);
        $balance_dump = 
            DB::table('employee_balances')
                ->where('employee_id', '=', $employee_id)
                ->first();

        $balance = $balance_dump->balance;
        return view('employees_admin.employee_finances_admin', ['employee' => $employee, 'balance' => $balance]);

        

    }

    /*
    ********** Блок начислений (credit) **********
    */

    public function employee_credit_page($employee_id){
        $employee = Employee::find($employee_id);

        return view('employees_admin.employee_credit_page', ['employee' => $employee]);

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
}
