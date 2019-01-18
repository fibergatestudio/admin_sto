<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_balance extends Model
{
	public function employee(){
        return $this->belongsTo('Employee'); // связь с моделью Employee
    }
}
