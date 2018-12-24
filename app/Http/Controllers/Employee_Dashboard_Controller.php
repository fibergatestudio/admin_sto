<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Assignment;

class Employee_Dashboard_Controller extends Controller
{
    /* Главная страница для ЛК сотрудника */
    public function index(){
        return view('employee.employee_dashboard_index');
    }

    /* Показать наряды сотрудника */
    public function my_assignments(){
        $user = Auth::user();
        $employee_user_id = $user->id;
        $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        $employee_id = $employee->id;
        

        // ... !!! ID пользователя и ID сотрудника отличаются
        // Получить employee_id
        

        /* Получаем информацию о нарядах */
        $assignments_data =
            DB::table('assignments')
                ->where(
                    [
                        ['responsible_employee_id', '=', $employee_id],
                        ['status', '=', 'active']
                    ]
                )
                ->get();
        
        return view('employee.assignments_index', ['assignments' => $assignments_data]);
    }

    /* Один наряд сотрудника : управление */
    public function manage_assignment($assignment_id){
        $assignment = Assignment::find($assignment_id); // Получаем наряд
        // .. Собираем информацию по наряду
        // .. Собираем историю по наряду
        
        // ..
    }
    
    /* Архив нарядов сотрудника */
    public function my_assignments_archive(){
        // ...
    }
}
