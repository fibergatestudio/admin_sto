<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Employee;
use App\Employees_logs;
use App\Employees_notes_logs;
use App\Employee_balance_logs;
use App\Client;
use App\Clients_logs;
use App\Clients_notes_logs;
use App\Cars_in_service;
use App\Cars_logs;
use App\Cars_notes_logs;
use App\Assignment;
use App\Sub_assignment;
use App\Workzone;
use App\User;
use App\Assignment_logs;
use Telegram\Bot\Laravel\Facades\Telegram;

class Logs_Admin_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     
    public function index()
    {
        return view('admin.logs.logs');
    } 
    */

    /* - Логи по сотрудникам - */
    public function employees_logs()
    {
        $employees_logs = Employees_logs::all();

        /* - Логи по заметкам сотрудников - */
        $employees_notes_logs = Employees_notes_logs::all();

        return view('admin.logs.employees_logs.employees_logs', compact('employees_logs', 'employees_notes_logs'));
    }

    /* - Логи по клиентам - */
    public function clients_logs()
    {
        $clients_logs = Clients_logs::all();

        /* - Логи по заметкам клиентов - */
        $clients_notes_logs = Clients_notes_logs::all();

        return view('admin.logs.clients_logs.clients_logs', compact('clients_logs', 'clients_notes_logs'));
    }

    /* - Логи по машинам в сервисе - */
    public function cars_in_service_logs()
    {
        $cars_in_service_logs = Cars_logs::all();

        /* - Логи по заметкам машин - */
        $cars_in_service_notes_logs = Cars_notes_logs::all();

        return view('admin.logs.cars_in_service_logs.cars_in_service_logs', compact('cars_in_service_logs', 'cars_in_service_notes_logs'));
    }

    /* - Логи по финансам - */
    public function finances_logs()
    {

        $paginate_finances_logs = Employee_balance_logs::orderBy('updated_at', 'desc')->paginate(10);

        foreach ($paginate_finances_logs as $employee_finances_log_entry) {

            $employee_id = $employee_finances_log_entry->employee_id;
            $employee_name = Employee::find($employee_id)->general_name;
            $employee_phone = Employee::find($employee_id)->phone;

            $action_choose = $employee_finances_log_entry->action;

            if ($employee_finances_log_entry->author_id) {
                $employee_finances_log_entry->author = User::find($employee_finances_log_entry->author_id)->general_name;
            }
            else{
                $employee_finances_log_entry->author = $employee_name;
            }

            if ($employee_phone) {
                $employee_finances_log_entry->phone = $employee_phone;
            }
            else{
                $employee_finances_log_entry->phone = 'Объект';
            }

            if($action_choose == 'deposit')
            {

                $text = 'Сотрудник : '. $employee_name . ' получил начисление : '. $employee_finances_log_entry->amount . ' на основании : '. $employee_finances_log_entry->reason . '. Было на балансе : '. $employee_finances_log_entry->old_balance . '. Стало на балансе : '. ($employee_finances_log_entry->old_balance + $employee_finances_log_entry->amount);

                $employee_finances_log_entry->employee_name = $employee_name;
                $employee_finances_log_entry->new_balance = $employee_finances_log_entry->old_balance + $employee_finances_log_entry->amount;
            }
            else
            {
                $text = 'С cотрудника : '. $employee_name . ' списано : '. $employee_finances_log_entry->amount . ' на основании : '. $employee_finances_log_entry->reason . '. Было на балансе : '. $employee_finances_log_entry->old_balance . '. Стало на балансе : '. ($employee_finances_log_entry->old_balance - $employee_finances_log_entry->amount);

                $employee_finances_log_entry->employee_name = $employee_name;
                $employee_finances_log_entry->new_balance = $employee_finances_log_entry->old_balance - $employee_finances_log_entry->amount;
            }

            $employee_finances_log_entry->text = $text;
        }           

        $employees_finances_logs = $paginate_finances_logs;

        return view ('admin.logs.finances_logs.finances_logs', compact('employees_finances_logs'));
        
    }


    /* - Логи по нарядам - */
    public function assignments_logs()
    {

        $paginate_assignments_logs = Assignment_logs::orderBy('updated_at', 'desc')->paginate(10);
       
        $assignments_logs = $paginate_assignments_logs;

        return view ('admin.logs.assignments_logs.assignments_logs', compact('assignments_logs'));
        
    }
     
    
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove  the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
