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

        /* Возвращаемся к списку всех рабочих постов */
        return redirect('admin/workzones/index');
    }

    /* Редактирование информации о рабочем посте*/
    public function edit_workzone($workzone_id){
        // ...
    }
}
