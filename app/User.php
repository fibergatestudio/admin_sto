<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Employee;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /* Проверка, является ли пользователь администратором */
    public function isAdmin(){
        if($this->role == 'admin')
        {
            return true;
        } else{
            return false;
        }
    }

    /* Проверка, является ли пользователь сотрудником */
    public function isEmployee(){
        if($this->role == 'employee')
        {
            return true;
        } else{
            return false;
        }
    }

    /* Проверка, является ли пользователь снабженцем */
    public function isSupplyOfficer(){
        if($this->role == 'supply_officer')
        {
            return true;
        } else{
            return false;
        }
    }

    /* Проверка, является ли пользователь кдиентом */
    public function isClient(){
        if($this->role == 'client')
        {
            return true;
        } else{
            return false;
        }
    }
    /* Проверка, является ли пользователь мастером */
    public function isMaster()
    {
        if ($this->role == 'master') {
            return true;
        } else {
            return false;
        }
    }

    /* Получить баланс пользователя */
    public function getBalance(){
        /* Проверяем, является ли пользователь сотрудником - если нет, то возвращаем N/A */
        if(!$this->isEmployee()){
            return 'N/A';
        }
        
        /* Получаем employee по user.id */
        $employee_data = Employee::where('user_id', $this->id)->first();
        return $employee_data->balance;


    }

}
