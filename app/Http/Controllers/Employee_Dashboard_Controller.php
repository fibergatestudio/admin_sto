<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Assignment;
use App\Shift;
use App\Assignments_income;
use App\Assignments_expense;
use App\Assignments_completed_works;

class Employee_Dashboard_Controller extends Controller
{
    /* Главная страница для ЛК сотрудника */
    public function index(){
        return view('employee.employee_dashboard_index');
    }

    /***** НАРЯДЫ *****/

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
                    ]
                )
                ->get();
        
        return view('employee.assignments_index', ['assignments' => $assignments_data]);
    }

    public function my_complete_assignments(){
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
                        ['status', '=', 'complete']
                    ]
                )
                ->get();
        
        
        return view('employee.my_completed_assignments', ['assignments' => $assignments_data]);
    }

    public function my_uncomplete_assignments(){
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
        
        
        
        return view('employee.my_uncompleted_assignments', ['assignments' => $assignments_data]);
    }

    /* Один наряд сотрудника : управление */
    public function manage_assignment($assignment_id){
        /* Получаем наряд */
        $assignment = Assignment::find($assignment_id); 
        
        // .. Собираем информацию по наряду
        
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::where('assignment_id', $assignment_id)->get();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::where('assignment_id', $assignment_id)->get();
        /* Получаем выполненые работы */
        $assignment_work = Assignments_completed_works::where('assignment_id', $assignment_id)->get();
        
        
        // .. Собираем историю по наряду
        
        /* Возвращаем страницу */
        return view('employee.assignment', 
        [
            'assignment' => $assignment, 
            'assignment_income' => $assignment_income, 
            'assignment_expense' => $assignment_expense, 
            'assignment_work' => $assignment_work
        ]);
    }

    /* Пометить наряд выполнено */
    public function assignment_complete($assignment_id){

        $complete = 'complete';

        $complete_assignment = Assignment::find($assignment_id);
        $complete_assignment->status = $complete;
        $complete_assignment->save();

        /* Возвращаемся обратно на страницу нарядов */
        return back();

    }

    /* Пометить наряд невыполнено */
    public function assignment_uncomplete($assignment_id){

        $uncomplete = 'active';

        $uncomplete_assignment = Assignment::find($assignment_id);
        $uncomplete_assignment->status = $uncomplete;
        $uncomplete_assignment->save();

        /* Возвращаемся обратно на страницу нарядов */
        return back();

    }
    

    /* Добавить заход денег : POST */
    public function add_income_post(Request $request){
        /* Создаём новое вхождение по заходу денег и вносим туда информацию */
        $new_income_entry = new Assignments_income();
        $new_income_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_income_entry->amount = $request->amount; /* Сумма захода */
        $new_income_entry->basis = $request->basis; /* Основание для захода денег */
        $new_income_entry->description = $request->description; /* Описание для захода */
        $new_income_entry->save();


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить расход денег : POST */
    public function add_expense_post(Request $request){
        /* Создаём новое вхождение по расходу денег и вносим туда информацию */
        $new_expense_entry = new Assignments_expense();
        $new_expense_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_expense_entry->amount = $request->amount; /* Сумма расхода */
        $new_expense_entry->basis = $request->basis; /* Основание для расхода денег */
        $new_expense_entry->description = $request->description; /* Описание для расхода */
        $new_expense_entry->save();


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить выполненые работы : POST */
    public function add_works_post(Request $request){
        /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
        $new_works_entry = new Assignments_completed_works();
        $new_works_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
        $new_works_entry->basis = $request->basis; /* Основание для расхода денег */
        $new_works_entry->description = $request->description; /* Описание для расхода */
        $new_works_entry->save();


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }

    /* Архив нарядов сотрудника */
    public function my_assignments_archive(){
        // ...
    }

    /**** Смены ****/

    /* Главная страница управления сменами */
    public function shifts_index(){
        // Получить сегодняшнюю смену
        // ...
        $today = date('Y-m-d');
        $today_shift = Shift::where('date', $today);
        

        return view('employee.shifts.shifts_index',
            [
                'today_shift' => $today_shift
            ]);
    }

    /* Открыть смену */
    public function start_shift(){
        // Получить ID сотрудника
        $employee_user_id = Auth::user()->id;

        // Проверить, открыта ли смена
        // ...

        // Если смена не открыта - открыть новую смену
        $new_shift = new Shift();
        $new_shift->new_shift($employee_user_id);

        // Вернуться на страницу управления сменами
        return back();

    }

    /* Закрыть смену */
    public function end_shift(Request $request){
        $shift_id = $request->shift_id;

        // ... Проверка на право закрыть
        // ... Проверка на статус смены (открыта ли?)

        // Закрываем смену
        $shift = Shift::find($shift_id);
        $shift->closed_at = date('H:i:s');
        $shift->status = 'closed';
        $shift->save();

        // И возвращаемся на страницу смен
        return back();

    }
}
