<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Workzone;
use App\Workzone_logs;
use App\User;


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
        $create_workzone_log_entry = new Workzone_logs();
        $create_workzone_log_entry->workzone_id = $new_workzone->id;
        $create_workzone_log_entry->author_id = Auth::user()->id;  //id автора
        /* employee_id test*/
        $create_workzone_log_entry->employee_id = Auth::user()->id;  //id автора

        /* - Имя автора - */
        $author_id =  $create_workzone_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_workzone_log_entry->text = 'Создана рабочая зона - ' .$new_workzone->id. ' автор создания - ' .$author_name. ' дата - ' .date('Y-m-d');  //
        /* Тип? Тест */
        $create_workzone_log_entry->type = '';
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
