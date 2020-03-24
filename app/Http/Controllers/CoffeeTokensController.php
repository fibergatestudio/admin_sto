<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Employee;
use App\Coffee_token_log;
use App\Employee_balance_logs;

class CoffeeTokensController extends Controller
{

    public function getPhone(Request $request){

        if ($request->phone) {
        
            $employee = Employee::where('phone', $request->phone)->first();

            if ($employee) {        

                $employee_id = $employee->id;
                $token_count = 1;

                // Вычесть стоимость жетонов с баланса
                $token_price = 5; // Сделать подтягивание с базы
                $token_total = $token_price * $token_count;
                $employee_balance = DB::table('employees')->where('id', '=', $employee_id)->first();
                $emp_balance = $employee_balance->balance;
                $new_employee_balance = $employee_balance->balance - $token_total;

                DB::table('employees')
                ->where('id', '=', $employee_id)
                ->update(['balance' => $new_employee_balance]);

                // Добавить жетоны в историю
                $employee_coffee_log_entry = new Coffee_token_log;
                $employee_coffee_log_entry->token_count = $token_count;
                $employee_coffee_log_entry->date = date('Y-m-d');
                $employee_coffee_log_entry->employee_id = $employee_id;
                $employee_coffee_log_entry->old_balance = $emp_balance;
                $employee_coffee_log_entry->save();

                // Добавить запись в общие логи
                $employee_balance_log = new Employee_balance_logs;
                $employee_balance_log->amount = $token_total;
                $employee_balance_log->reason = 'Списание за выдачу жетонов кофе';
                $employee_balance_log->action = 'payout';
                $employee_balance_log->source = 'auto';
                $employee_balance_log->type = 'Кофе'; // Тип записи
                $employee_balance_log->status = 'Принят(тест)'; // Статус
                $employee_balance_log->date = date('Y-m-d');
                $employee_balance_log->employee_id = $employee_id;
                $employee_balance_log->old_balance = $emp_balance;
                $employee_balance_log->save();

                return 'Жетон кофе с работника '.$employee->general_name.' успешно списан !';
            }
            else{
                return 'Нет такого работника !';
            }
        }
        else{
            return 'Введите номер телефона !';
        }
    }
}
