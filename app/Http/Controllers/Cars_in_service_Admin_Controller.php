<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Client;
use App\Cars_in_service;

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

    /* Добавление машины : POST */
    public function add_car_post(Request $request){
        /* Добавить машину в БД */
        $new_car_in_service = new Cars_in_service();
        $new_car_in_service->general_name = $request->car_general_name;
        $new_car_in_service->owner_client_id = $request->client_id;
        $new_car_in_service->save();

        /* И перенаправить на страницу клиента */
        return redirect()->route('admin_view_client', ['client_id' => $request->client_id]);
    }

    /* Страница машины : просмотр */
    public function single_car_view($car_id){
        $car_in_service = Cars_in_service::find($car_id);
        $client = Client::find($car_in_service->owner_client_id);


        return view('admin.cars_in_service.single_car_page', [
            'car' => $car_in_service,
            'client' => $client
        ]);
    }


}
