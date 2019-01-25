<?php

namespace App\Http\Controllers;
use App\Assignment;
use App\Client;
use Auth;
use App\Employee;
use App\employee_fine;
use App\Sub_assignment;
use App\Cars_in_service;

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
    /* Главная страница активных нарядов клиента */
    public function assignments($id)
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
            return $element->status == 'archived'; //return assignments where status=='active'
        });

        if(!$assignments->isEmpty()) {
            $empty = 0;
            return view('client.assignments')->with(array('assignments' => $assignments,'empty'=>$empty));
        }
        else
        {
            $empty = 1;
            return view('client.assignments')->with(array('empty'=>$empty));
        }

    }

    /* Главная страница зональных нарядов клиента */
    public function sub_assignments($id)
    {

        $sub_assignments =
            Sub_assignment::where('assignment_id', $id)->join('workzones', 'sub_assignments.workzone_id', '=', 'workzones.id')->select('sub_assignments.*', 'workzones.general_name')->get();

        foreach($sub_assignments as $sub_assignment){
            $sub_assignment->responsible_employee = Employee::find($sub_assignment->responsible_employee_id)->general_name;
        }

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

    public function master()
    {
        return view('master.master');
    }

    public function master_employees() //просмотр работников
    {
        $employees = Employee::get();
        return view('master.employees')->with(array('employees' => $employees));
    }

    public function employee_finances($id) //просмотр финансов работника мастером
    {
        $employee = Employee::where('id',$id)->get();
        $employee_fines = employee_fine::where('employee_id', $id)->get();
        return view('master.employee_finances')->with(array('employee' => $employee[0],'employee_fines'=>$employee_fines));
    }

}
