<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = "employees";
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
    

    //protected $table = 'employee_balances';// таблица связанная с моделью (начисление баланса сотруднику)

    //вместо выше написанной строки
    //public function employee_balance(){
    //    return $this->hasOne('Employee_balance'); // связь с моделью Employee_balance (начисление баланса сотруднику)
    //}

}
