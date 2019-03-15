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
        $workzones = Workzone::find($workzone_id);
        $name=$workzones->general_name;
        $description=$workzones->description;
        return view('admin.workzones.edit_workzone',[
            'workzones_id'=>$workzone_id,
            'names'=>$name, 
            'descriptions'=>$description,
        ]);

    }
    public function edit_workzone_id(Request $request){
     /* Меняем название наряда */
     $edit_workzones_id=$request->workzones_id;
     $edit_workzones = Workzone::find($edit_workzones_id);
     $edit_workzones->general_name = $request->general_name;
     $edit_workzones->description = $request->description;
     $edit_workzones->save();

     /* Возвращаемся к списку всех рабочих постов */
     return redirect('admin/workzones/index');
 }

 public function delete_workzone($workzone_id){
     /* Меняем название наряда */
     Workzone::find($workzone_id)->delete();
     /* Возвращаемся к списку всех рабочих постов */
     return redirect('admin/workzones/index');
 }
}
