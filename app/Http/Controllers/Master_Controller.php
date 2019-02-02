<?php

namespace App\Http\Controllers;
use App\Car_model_list;
use Illuminate\Http\Request;
use App\Assignment;
use App\Client;
use App\Employee;
use App\employee_fine;
use App\Sub_assignment;
use App\Workzone;
use Auth;
use App\Cars_in_service;
use App\Assignments_income;
use App\Assignments_expense;
use App\Assignments_completed_works;

class Master_Controller extends Controller
{

    /* Главная страница мастера */
    public function master()
    {
        return view('master.master');
    }
//Просмотр всех нарядов мастером
    public function master_assignments()
    {

        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
            Assignment::where('confirm','1')->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->select(
                    'assignments.*',
                    'employees.general_name AS employee_name',
                    'cars_in_service.general_name AS car_name'
                )
                ->get();

        return view('master.assignments')->with(array('assignments' => $assignments_data,'empty'=>0));
    }
//Просмотр наряда на странице мастера
    public function master_view_assignment($id){

//dd($id);
        /* Получаем информацию по наряду */
        $assignment = Assignment::find($id);

        /* Получаем дополнительную информацию по нарядам */
        /* Имя клиента */
        $assignment->client_name = $assignment->get_client_name();
        /* Авто */
        $assignment->car_name = $assignment->get_car_name();
        /* Получаем список зональных нарядов */
        $sub_assignments =
            Sub_assignment::where('assignment_id', $id)
                ->join('workzones', 'sub_assignments.workzone_id', '=', 'workzones.id')
                ->select('sub_assignments.*', 'workzones.general_name')
                ->get();


        // dd($sub_assignments);
        /* Собираем дополнительные данные по зональным нарядам */
        foreach($sub_assignments as $sub_assignment){
            /* Название рабочей зоны */
            $sub_assignment->workzone_name = Workzone::find($sub_assignment->workzone_id)->general_name;

            /* Имя ответственного работника */
            $sub_assignment->responsible_employee = Employee::find($sub_assignment->responsible_employee_id)->general_name;
        }

        /* Доход/расход/работы */
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::where('assignment_id', $id)->get();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::where('assignment_id', $id)->get();
        /* Получаем выполненые работы */
        $assignment_work = Assignments_completed_works::where('assignment_id', $id)->get();
        /* Получаем все зоны */
        $all_workzones = Workzone::pluck('general_name');

        return view('master.view_assignment_page')->with(array('assignment' => $assignment,'sub_assignments' => $sub_assignments,'assignment_income' => $assignment_income, 'assignment_expense' => $assignment_expense, 'assignment_work' => $assignment_work ,'workzones'=>$all_workzones));

    }



    /* Применение изменений редактирования доходной части */
    public function income_entry(Request $request){

        $id = $request->id;
        $new_amount =  $request->new_amount;
        $new_currency = $request->new_currency;
        $new_basis = $request->new_basis;
        $new_description = $request->new_description;
        Assignments_income::where('id',$id)->update(['amount'=>$new_amount,'basis'=>$new_basis,'description'=>$new_description,'currency'=>$new_currency]);

        /* Возвращаемся на страницу */
        return back();
    }


    /* Применение изменений редактирования расходной части */
    public function expense_entry(Request $request){

        $id = $request->id;
        $new_amount =  $request->new_amount;
        $new_currency = $request->new_currency;
        $new_basis = $request->new_basis;
        $new_description = $request->new_description;
        Assignments_expense::where('id',$id)->update(['amount'=>$new_amount,'basis'=>$new_basis,'description'=>$new_description,'currency'=>$new_currency]);

        /* Возвращаемся на страницу */
        return back();
    }

    /* Применение изменений редактирования наряда */
    public function redact_subassignments(Request $request){

        $id = $request->id;
        //  dd($id);
        $new_name = $request->new_name;//Название
        $new_workzone_name = $request->new_workzone;//название рабочей зоны
        $workzone_id = Workzone::where('general_name',$new_workzone_name)->pluck('id')[0];
        Sub_assignment::where('id',$id)->update(['name'=>$new_name,'workzone_id'=>$workzone_id]);

        /* Возвращаемся на страницу */
        return back();
    }


    /* Применение изменений редактирования расходной части */
    public function work_entry(Request $request){

        $id = $request->id;
        $new_basis = $request->new_basis;
        $new_description = $request->new_description;
        Assignments_completed_works::where('id',$id)->update(['basis'=>$new_basis,'description'=>$new_description]);

        /* Возвращаемся на страницу */
        return back();
    }


    public function master_employees()
    {
        $employees = Employee::get();
        return view('master.employees')->with(array('employees' => $employees));
    }

    public function employee_finances($id)
    {
        $employee = Employee::where('id',$id)->get();
        $employee_fines = employee_fine::where('employee_id', $id)->get();
        return view('master.employee_finances')->with(array('employee' => $employee[0],'employee_fines'=>$employee_fines));
    }


}
