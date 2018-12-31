<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /* Получить employee.id по user.id работника */
    public static function get_employee_by_user_id($employee_user_id){
        $employee = Employee::where('user_id', $employee_user_id)->first();
        return $employee;
    }

    /* Задать новую ставку за смену */
    public function set_new_wage($wage){
        $this->standard_shift_wage = $wage;
        $this->save();
    }
    
}
