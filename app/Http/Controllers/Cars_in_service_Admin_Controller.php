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

class Cars_in_service_Admin_Controller extends Controller
{
    
    /* Все машины : страница */
    public function index(){
        //$cars_in_service = Cars_in_service::all();

        $cars_in_service = 
            DB::table('cars_in_service')
                ->join('clients', 'cars_in_service.owner_client_id', '=', 'clients.id')
                ->select('cars_in_service.*', 'clients.general_name as client_name')
                ->get();

        return view('admin.cars_in_service.cars_in_service_Index', ['cars' => $cars_in_service]);
    }
    
    /* Добавление машины : страница */
    public function add_car($client_id = ''){
        /* Если клиент указан*/
        if(!empty($client_id)){
            $client = Client::find($client_id);
            return view('admin.cars_in_service.add_car_in_service', ['client' => $client]);
        } else {
        /* Если клиент не указан */
            $clients = Client::all();
            return view('admin.cars_in_service.add_car_in_service', ['client' => '', 'clients' => $clients]);
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
        $new_car_in_service->general_name = $request->car_general_name;
        $new_car_in_service->owner_client_id = $request->client_id;
        $new_car_in_service->reg_number = $request->reg_number;
        $new_car_in_service->fuel_type = $request->fuel_type;
        $new_car_in_service->vin_number = $request->vin_number;
        $new_car_in_service->engine_capacity = $request->engine_capacity;
        $new_car_in_service->save();

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

        return redirect('admin/cars_in_service/view/' .$car_note_entry->car_id);
    }

    /* Удаление примечания к машине */
    public function delete_note($note_id){
        // Удалить примечание
        Cars_notes::find($note_id)->delete();
        // И вернуться на страницу машины
        return back();
    }
    


}
