<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee_balances';// таблица связанная с моделью (начисление баланса сотруднику)
}
