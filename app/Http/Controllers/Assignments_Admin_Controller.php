<?php
/*
* Контроллер админ версии нарядов
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Assignments_Admin_Controller extends Controller
{
    /* Отображения списка всех нарядов */
    public function assignments_index(){
        return view('assignments_admin.assignments_admin_index');
    }

    /* Добавления наряда: страница с формой */
    public function add_assignment_page($car_id = ''){
        echo 'Страница добавления наряда по машине';
    }
    
    /* Добавления наряда: страница обработки POST данных*/
    public function add_assignment_page_post(Request $request){

    }
}
