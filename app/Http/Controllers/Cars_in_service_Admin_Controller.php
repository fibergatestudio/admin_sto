<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Client;
use App\Cars_in_service;
use App\Cars_notes;
use App\Assignment;
use App\Car_model_list;
use App\Cars_logs;
use App\Cars_notes_logs;
use App\Fuel_type;

//TEST NOTE DELETE
use App\Deleted_notes;

class Cars_in_service_Admin_Controller extends Controller
{

    /* Все машины : страница */
    public function index(){
        //$cars_in_service = Cars_in_service::all();

        $cars_in_service =
            DB::table('cars_in_service')
                ->join('clients', 'cars_in_service.owner_client_id', '=', 'clients.id')
                ->select('cars_in_service.*', 'clients.fio as client_name')
                ->get();

        return view('admin.cars_in_service.cars_in_service_Index', ['cars' => $cars_in_service]);
    }

    /* Добавление машины : страница */
    public function add_car($client_id = ''){
        /* Если клиент указан*/
        if(!empty($client_id)){
            $client = Client::find($client_id);

            $fuel_type = DB::table('fuel_type')->get();

            return view('admin.cars_in_service.add_car_in_service', ['client' => $client, 'fuel_type' => $fuel_type]);
        } else {
        /* Если клиент не указан */
            $clients = Client::orderByDesc('created_at')->get();

            $fuel_type = DB::table('fuel_type')->get();

            return view('admin.cars_in_service.add_car_in_service', ['client' => '', 'clients' => $clients, 'fuel_type' => $fuel_type]);
        }
    }

    /* API для брендов машин */
    /* Возвращает список всех брендов авто */
    public function api_brands(){
        $brands = Car_model_list::select('brand')->distinct()->get();
        $brands_array = [];
        foreach($brands as $brand){
            $brands_array[] = $brand->brand;
        }
        print_r(json_encode($brands_array));
    }

    /* API для моделей машин (возвращают список по бренду) */
    public function api_models($brand){
        /* Получить список моделей */
        $models = Car_model_list::select('model')->where('brand', $brand)->distinct()->get();
        $model_array = [];
        foreach($models as $model){
            $model_array[] = $model->model;
        }

        /* Вывести в JSON формате */
        print_r(json_encode($model_array));
    }

    /* Добавление машины : POST */
    public function add_car_post(Request $request){
        /* Добавить машину в БД */

        $new_car_in_service = new Cars_in_service();

        /* explode */
        // $damp = explode(" ", $request->car_general_name);

        // $new_car_in_service->general_name = $damp[1];

        $new_car_in_service->general_name = $request->car_general_name;

        //dd($request->car_general_name);
        $new_car_in_service->owner_client_id = $request->client_id;
        $new_car_in_service->release_year = $request->release_year;
        $new_car_in_service->reg_number = $request->reg_number;
        $new_car_in_service->fuel_type = $request->fuel_type;
        $new_car_in_service->vin_number = $request->vin_number;
        $new_car_in_service->engine_capacity = $request->engine_capacity;
        $new_car_in_service->car_color = $request->car_color;
        $new_car_in_service->mileage_km = $request->mileage_km;
        $new_car_in_service->car_brand = $request->car_brand;
        $new_car_in_service->car_model = $request->car_model;
        $new_car_in_service->save();

        /* - Добавдение создания машины в логи - */
        $create_car_in_service_log_entry = new Cars_logs();
        $create_car_in_service_log_entry->car_id = $new_car_in_service->id;  //id машины
        //$create_car_in_service_log_entry->client_id = $request->client_id;  //id клиента
        $create_car_in_service_log_entry->author_id = Auth::user()->id;  //id автора

        //$create_car_in_service_log_entry->save();

        /* - Название машины  - */
        $car_id = $create_car_in_service_log_entry->car_id;
        $car = Cars_in_service::find($car_id);
        $car_name = $request->car_general_name;

        /* - Имя клиента - */
        $client_id = $request->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;

        /* - Имя автора - */
        $author_id = $create_car_in_service_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_car_in_service_log_entry->text = 'Создана машина - '.$car_name. ' | клиента - '.$client_name. ' | автор - '.$author_name;   //текст лога о создании машины(название) клиента(имя) от автора(имя)
        /* Тип? Тест */
        $create_car_in_service_log_entry->type = '';
        $create_car_in_service_log_entry->save();

        //$request->document->store('public1'); //Заливка файла

        if(!empty($request->document)){
            $request->document->store('public1');
        }

        /* И перенаправить на страницу клиента */
        return redirect()->route('admin_view_client', ['client_id' => $request->client_id]);
    }

    /* Изменение машины : POST */
    public function update_car_post(Request $request, $car_id){

        $new_car_in_service = Cars_in_service::find($car_id);

        $new_car_in_service->general_name = $request->car_general_name;
        $new_car_in_service->release_year = $request->release_year;
        $new_car_in_service->reg_number = $request->reg_number;
        $new_car_in_service->fuel_type = $request->fuel_type;
        $new_car_in_service->vin_number = $request->vin_number;
        $new_car_in_service->car_color = $request->car_color;
        $new_car_in_service->engine_capacity = $request->engine_capacity;
        $new_car_in_service->mileage_km = $request->mileage_km;
        $new_car_in_service->car_brand = $request->car_brand;
        $new_car_in_service->car_model = $request->car_model;
        $new_car_in_service->save();

        /* - Добавдение об обновлении машины в логи - */
        $create_car_in_service_log_entry = new Cars_logs();
        $create_car_in_service_log_entry->car_id = $new_car_in_service->id;  //id машины
        //$create_car_in_service_log_entry->client_id = $request->client_id;  //id клиента
        $create_car_in_service_log_entry->author_id = Auth::user()->id;  //id автора

        //$create_car_in_service_log_entry->save();

        /* - Название машины  - */
        $car_id = $create_car_in_service_log_entry->car_id;
        $car = Cars_in_service::find($car_id);
        $car_name = $request->car_general_name;

        /* - Имя клиента - */
        $client_id = $new_car_in_service->owner_client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;

        /* - Имя автора - */
        $author_id = $create_car_in_service_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_car_in_service_log_entry->text = 'Обновлена машина - '.$car_name. ' | клиента - '.$client_name. ' | автор - '.$author_name;   //текст лога о создании машины(название) клиента(имя) от автора(имя)
        /* Тип? Тест */
        $create_car_in_service_log_entry->type = '';
        $create_car_in_service_log_entry->save();

        //$request->document->store('public1'); //Заливка файла

        if(!empty($request->document)){
            $request->document->store('public1');
        }

        /* И перенаправить на страницу клиента */
        return redirect()->route('admin_view_client', ['client_id' => $request->client_id]);
    }

    /* Страница машины : просмотр */
    public function single_car_view($car_id){
        $car_in_service = Cars_in_service::find($car_id);

        // Получаем информацию о клиенте
        $client = Client::find($car_in_service->owner_client_id);

        /* Получаем информацию об активных нарядах на машину */
        $assignments = Assignment::where([['car_id', $car_id], ['status', 'active']])->get();

        /* Получаем информацию о примечаниях к машине */
        $car_notes =
            DB::table('cars_notes')
                ->where('car_id', $car_in_service->id)
                ->get();

        // Получаем имя автора
        foreach($car_notes as $car_note){
            $car_note->author_name = User::find($car_note->author_id)->general_name;
        }

        /* Выводим представление */
        return view('admin.cars_in_service.single_car_page', [
            'car' => $car_in_service,
            'client' => $client,
            'car_notes' => $car_notes,
            'assignments' => $assignments
        ]);
    }

    /* Страница нарядов на машину */
    public function car_assignments_view($car_id){

        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
        DB::table('assignments')
            ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
            ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
            ->join('new_sub_assignments', 'assignments.id', '=', 'new_sub_assignments.assignment_id')
            ->orderBy('order','ASC')
            ->select(
                    'assignments.*',
                    'employees.general_name AS employee_name',
                    'cars_in_service.general_name AS car_name',
                    'cars_in_service.vin_number AS vin_number',
                    'cars_in_service.release_year AS release_year',
                    'cars_in_service.reg_number AS reg_number',
                    'cars_in_service.car_color AS car_color',
                    'new_sub_assignments.d_table_workzone AS assignment_workzone'
                )
            ->where([
                ['new_sub_assignments.work_row_index', '<>', null],
                ['assignments.status', '=', 'active'],
                ['car_id', '=', $car_id]
            ])
            ->get();
        $workzone_data = DB::table('workzones')->get();
    
        // Собираем зональные наряды в массив
        if($assignments_data->count()){
            $temp_arr_obj = [];
            $temp_arr_workzone = [];
            $temp_id = $assignments_data[0]->order;
            $i = 0;
    
            for ( ;$i < count($assignments_data); $i++) {
                if ($temp_id == $assignments_data[$i]->order) {
                    $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
                }
                else{
                    $temp_arr_obj[$i-1] = $assignments_data[$i-1];
                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    $temp_id = $assignments_data[$i]->order;
                    $temp_arr_workzone = [];
                    $temp_arr_workzone[] = $assignments_data[$i]->assignment_workzone;
                }
            }
    
            $temp_arr_obj[$i-1] = $assignments_data[$i-1];
            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
    
            $assignments_data = $temp_arr_obj;
        }
    
        $assignments_list =
        DB::table('assignments')
            ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
            ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
            //->join('new_sub_assignments', 'assignments.id', '=', 'new_sub_assignments.assignment_id')
            ->orderBy('order','ASC')
            ->select(
                    'assignments.*',
                    'employees.general_name AS employee_name',
                    'cars_in_service.general_name AS car_name',
                    'cars_in_service.vin_number AS vin_number',
                    'cars_in_service.release_year AS release_year',
                    'cars_in_service.reg_number AS reg_number',
                    'cars_in_service.car_color AS car_color'
                    //'new_sub_assignments.d_table_workzone AS assignment_workzone'
                )
            ->where([
                //['new_sub_assignments.work_row_index', '<>', null],
                ['assignments.status', '=', 'active']
            ])
            ->get();
    
        if($assignments_list->count()){
            $temp_arr_obj = [];
            $temp_arr_workzone = [];
            $temp_id = $assignments_list[0]->order;
            $i = 0;
    
            for ( ;$i < count($assignments_list); $i++) {
                if ($temp_id == $assignments_list[$i]->order) {
                    //$temp_arr_workzone[] = $assignments_list[$i]->assignment_workzone;
                }
                else{
                    $temp_arr_obj[$i-1] = $assignments_list[$i-1];
                    $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
                    $temp_id = $assignments_list[$i]->order;
                    //$temp_arr_workzone = [];
                    //$temp_arr_workzone[] = $assignments_list[$i]->assignment_workzone;
                }
            }
    
            $temp_arr_obj[$i-1] = $assignments_list[$i-1];
            $temp_arr_obj[$i-1]->workzone = $temp_arr_workzone;
    
            $assignments_list = $temp_arr_obj;
        }

        $car_info = DB::table('cars_in_service')->where('id', $car_id)->first();
    
        //echo '<pre>'.print_r($assignments_data,true).'</pre>';
        return view('admin.clients.view_client_assignments', 
        [
            'assignments' => $assignments_data, 
            'workzone_data' => $workzone_data, 
            'assignments_list' => $assignments_list,
            'car_info' => $car_info
        ]);
    }

    /* - Добавление примечания к машине : страница - */
    public function add_note_to_car_page($car_id){
        $car = Cars_in_service::find($car_id);
        $client = $car->get_client();
        return view('admin.cars_in_service.add_note_to_car', ['car' => $car, 'client' => $client]);
    }

    /* - Добавление примечания к машине : POST - */
    public function add_note_to_car_post(Request $request){
        // Добавить примечание
        $car = Cars_in_service::find($request->car_id);
        $new_car_note_entry = new Cars_notes();
        $new_car_note_entry->car_id = $car->id;
        $new_car_note_entry->author_id = Auth::user()->id;
        $new_car_note_entry->text = $request->note_content;
        $new_car_note_entry->type = 'note';
        $new_car_note_entry->save();


        /* Добавление в логи создание примечания по машине - */
        $create_car_note_log_entry = new Cars_notes_logs();
        $create_car_note_log_entry->car_id = $new_car_note_entry->car_id;  //id машины
        $create_car_note_log_entry->note_id = $new_car_note_entry->id;  //id примечания
        $create_car_note_log_entry->author_id = Auth::user()->id;  //id автора


        /* - Название машины - */
        $car_id = $create_car_note_log_entry->car_id;
        $car = Cars_in_service::find($car_id);
        $car_name = $car->general_name;
        /* - Имя клиента - */
        $client_id = $car->owner_client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $create_car_note_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_car_note_log_entry->text = 'Создание заметки по машине'.$car_name. ' | клиента - '.$client_name. ' | автор - '.$author_name;   //текст лога о созданиинии заметки по машине(название) клиента(имя) от автора(имя)
        /* Тип? Тест */
        $create_car_note_log_entry->type = '';
        $create_car_note_log_entry->save();


        // И вернуться на страницу машины
        return redirect('admin/cars_in_service/view/'.$car->id);
    }

    /* - Редактирование примечания к машине : старница - */
    public function edit_note_to_car($note_id){
        $car_note = Cars_notes::find($note_id);
        return view('admin.cars_in_service.edit_note_to_car',
            [
                'note_id' => $note_id,
                'car_note' => $car_note
            ]);


    }

    /* - Редактирование примечания к машине : POST - */
    public function edit_note_to_car_post(Request $request){
        $car_note_entry = Cars_notes::find($request->note_id);
        $car_note_entry->text = $request->text;
        $car_note_entry->save();

        /* - Добавление в логи создание заметки по машине - */
        $edit_car_note_log_entry = new Cars_notes_logs();
        $edit_car_note_log_entry->car_id = $car_note_entry->car_id;
        //$edit_car_note_log_entry->client_id = $request->client_id;
        $edit_car_note_log_entry->author_id = Auth::user()->id;
        $edit_car_note_log_entry->note_id = $car_note_entry->id;

        /* - Название машины - */
        $car_id = $car_note_entry->car_id;
        $car = Cars_in_service::find($car_id);
        $car_name = $car->general_name;
        /* - Имя клиента - */
        $client_id = $car->owner_client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $edit_car_note_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $edit_car_note_log_entry->text = 'Редактирование заметки по машине - '.$car_name. ' | клиента - '.$client_name. ' | автор - '.$author_name;  //текст лога о редактировании заметки по машине(название) клиента(имя) от автора(имя)
        /* Тип? Тест */
        $edit_car_note_log_entry->type = '';
        $edit_car_note_log_entry->save();

        return redirect('admin/cars_in_service/view/' .$car_note_entry->car_id);
    }

    /* Удаление примечания к машине */
    public function delete_note($note_id){

        $note_info = Cars_notes::find($note_id);

        /* - Добавление в логи удаление замтеки по машине - */
        $delete_car_note_log = new Deleted_notes();
        //$delete_car_note_log->car_id = $note_info->car_id;
        $delete_car_note_log->author_id = Auth::user()->id;
        $delete_car_note_log->note_id = $note_info->id;

        /* - Название машины - */
        $car_id = $note_info->car_id;
        $car = Cars_in_service::find($car_id);
        $car_name = $car->general_name;
        /* - Имя клиента - */
        $client_id = $car->owner_client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $delete_car_note_log->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $delete_car_note_log->text = 'Удаление заметки по машине -'.$car_name.' | клиента - '.$client_name.' | автор - '.$author_name;  //текст лога о удалении заметки по машине(название) клиента(имя) от автора(имя)
        /* Тип? Тест */
        $delete_car_note_log->type = '';
        $delete_car_note_log->save();

        // Удалить примечание
        Cars_notes::find($note_id)->delete();

        // И вернуться на страницу машины
        return back();
    }

    /* Добавление Типов Топлива */
    public function add_fuel(){

        // $new_fuel = array('fuel_name' => 'fuel_name');
        // Fuel_type::create($new_fuel);

        $fuels = Fuel_type::all();

        if($fuels->isEmpty()){
            $array = array('Дизель', 'Гибрид', 'Бензин', 'Природный газ', 'Сжиженный газ', 'Электрический');

            foreach ($array as $fuel){
                $new_fuel_entry = new Fuel_type();
                $new_fuel_entry->fuel_name = $fuel; /* Название */
                $new_fuel_entry->save();
            }
        }

        return back();
    }



}
