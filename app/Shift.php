<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Employee;

class Shift extends Model
{
    // Открыть новую смену
    public function new_shift($employee_user_id){
        // Получаем сотрудинка по его user_id
        // !!! user != employee
        // user используется для аутентификации и авторизации, а employee - это центральная сущность для информации о сотрудниках в приложении
        // Поскольку действия на открытие смены выполняется сотрудником как пользователем (user), нам нужно сначала получить сущность сотрудника по его id как пользователя
        $employee = Employee::get_employee_by_user_id($employee_user_id);
        
        // Наполняем строку смены необходимой информацией
        $this->employee_id = $employee->id;
        $this->date = date('Y-m-d');
        $this->opened_at = date('H:i:s');
        $this->status = 'active';
        $this->save();

        return true; // Уведомление об успешном создании смены
        
    }

    // Закрыть текущую смену
    public function close_shift(){
        // Меняем статус на закрыто
        $this->status = 'closed';
        $this->save();

        return true; // Уведомление об успешном закрытии смены
    }
}
