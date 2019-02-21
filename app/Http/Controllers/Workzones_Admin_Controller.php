<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Workzone;

class Workzones_Admin_Controller extends Controller
{

    /* Просмотр всех рабочих постов */
    public function index(){
        $workzones = Workzone::all();
        return view('admin.workzones.workzone_index', ['workzones' => $workzones]);
    }

    /* Добавление рабочего поста : страница */
    public function add_workzone(){
        return view('admin.workzones.add_workzone');
    }
    
    /* Добавление рабочего поста : действие */
    public function add_workzone_post(Request $request){
        /* Создаём новый рабочий пост */
        $new_workzone = new Workzone();
        $new_workzone->general_name = $request->general_name;
        $new_workzone->description = $request->description;
        $new_workzone->save();


        /* - Добавление в логи создание рабочего поста - */
        $create_workzone_log = new Workzone_logs();
        $create_workzone_log_entry->workzone_id = $workzone_id;
        $create_workzone_log_entry->author_id = $author_id;
        $create_workzone_log_entry->employee_id = $employee_id;
        
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name; 

        /* - Имя сотрудника - */
        $employee = Employees_logs::find($employee_id);
        $employee_name = $employee->general_name;

        $create_workzone_log_entry->text = 'Создана рабочая зона - ' .$workzone_id. ' автор создания - ' .$author_name. ' ответственный сотрудник - ' .$employee_name. ' дата - ' .date('Y-m-d');  //
        $create_workzone_log_entry->save();


        /* Возвращаемся к списку всех рабочих постов */
        return redirect('admin/workzones/index');
    }

    /* Редактирование информации о рабочем посте*/
    public function edit_workzone($workzone_id){
        // ...


        $edit_workzone_log = new Workzone_logs();
        $edit_workzone_log_entry->workzone_id = $workzone_id;
        $edit_workzone_log_entry->author_id = $author_id;

        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;

        $edit_workzone_log_entry->text = 'Отредактирована информация о рабочей зоне -' .$workzone_id. ' автор - ' .$author_name. 'дата - ' .date('Y-m-d');
        $edit_workzone_log_entry->save();
    }
}
