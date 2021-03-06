<?php

namespace App\Http\Controllers;
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

class Client_Controller extends Controller
{
    /* Главная страница клиента */
    public function client()
    {
        $id = Auth::id();
        $owner_id = Client::where('user_id',$id)->pluck('id');
        if(!$owner_id->isEmpty()) {
            $car = Cars_in_service::where('owner_client_id', $owner_id[0])->get();
            return view('client.client')->with(array('cars' => $car));
        }
    }
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
            Assignment::join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->select(
                    'assignments.*',
                    'employees.general_name AS employee_name',
                    'cars_in_service.general_name AS car_name'
                )
                ->get();
        // dd($assignments_data);
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

        return view('master.view_assignment_page')->with(array('assignment' => $assignment,'sub_assignments' => $sub_assignments,'assignment_income' => $assignment_income, 'assignment_expense' => $assignment_expense, 'assignment_work' => $assignment_work ,'empty'=>0));

    }

    /* Главная страница активных нарядов клиента */
    public function assignments($id)
    {

        //  $assignment = Assignment::where('car_id', $id)->where('status', 'active')->get();
        $assignments = Assignment::where('car_id', $id)
            ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
            ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
            ->select(
                'assignments.*',
                'employees.general_name AS employee_name',
                'cars_in_service.general_name AS car_name'
            )
            ->get();
        $assignments = $assignments->reject(function($element) {
            return $element->status == 'archived'; //return assignments where status=='active'
        });
        //dd($assignments);
        /*  if(!$assignments->isEmpty()) {
               return view('client.assignments')->with(array('assignments' => $assignments));
          }*/


        if(!$assignments->isEmpty()) {
            $empty = 0;
            return view('client.assignments')->with(array('assignments' => $assignments,'empty'=>$empty));
        }
        else
        {
            $empty = 1;
            return view('client.assignments')->with(array('empty'=>$empty));
        }

        //  $sub_assignment = Sub_assignment::where('assignment_id', $assignment[0]->id)->get();
        // dd($assignment);
        /*   if(!$assignment->isEmpty()) {
               $responsible_employee = Employee::where('id', $assignment[0]->responsible_employee_id)->get();
               return view('client.assignments')->with(array('assignments' => $assignment,'responsible_employee' => $responsible_employee[0]));

           }*/
    }

    /* Главная страница зональных нарядов клиента */
    /*  public function sub_assignments($id)
      {
         // $assignment = Assignment::where('car_id', $id)->where('status', 'active')->get();
          $sub_assignment = Sub_assignment::where('assignment_id', $id)->get();
         // dd($sub_assignment);
          $responsible_employee = Employee::where('id', $sub_assignment[0]->responsible_employee_id)->get();
          $workzone =Workzone::where('id', $sub_assignment[0]->workzone_id)->get();
          return view('client.sub_assignments')->with(array('sub_assignment' => $sub_assignment[0],'responsible_employee' => $responsible_employee[0]));
      }*/
    /* Главная страница зональных нарядов клиента */
    public function sub_assignments($id)
    {

        $sub_assignments =
            Sub_assignment::where('assignment_id', $id)->join('workzones', 'sub_assignments.workzone_id', '=', 'workzones.id')->select('sub_assignments.*', 'workzones.general_name')->get();

        foreach($sub_assignments as $sub_assignment){
            $sub_assignment->responsible_employee = Employee::find($sub_assignment->responsible_employee_id)->general_name;
        }
        //  dd($sub_assignments);

        return view('client.sub_assignments')->with(array('sub_assignments' => $sub_assignments));
    }

    /* Главная страница архивных нарядов клиента */
    public function assignments_archive($id)
    {

        $assignments = Assignment::where('car_id', $id)
            ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
            ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
            ->select(
                'assignments.*',
                'employees.general_name AS employee_name',
                'cars_in_service.general_name AS car_name'
            )
            ->get();
        $assignments = $assignments->reject(function($element) {
            return $element->status == 'active'; //return assignments where status=='archived'
        });
        //  dd($assignments);

        if(!$assignments->isEmpty()) {
            $empty = 0;
            return view('client.assignments_archiv')->with(array('assignments_archiv' => $assignments,'empty'=>$empty));
        }
        else
        {
            $empty = 1;
            return view('client.assignments_archiv')->with(array('empty'=>$empty));
        }

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
