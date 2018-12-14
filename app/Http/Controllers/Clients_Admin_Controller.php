<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Cars_in_service;

class Clients_Admin_Controller extends Controller
{
    
    /* Перечень клиентов */
    public function clients_index(){
        $clients = Client::all();
        return view('admin.clients.clients_index', ['clients' => $clients]);
    }

    /* Добавить клиента: страница */
    public function add_client_page(){
        return view('admin.clients.add_client_page');
    }

    /* Добавить клиента: обработка POST запроса */
    public function add_client_post(Request $request){
        $new_client = new Client();
        $new_client->general_name = $request->general_name;

        $new_client->save();
        // Если клиент был добавлен успешно, то предлагаем добавить машину клиента
        return view('admin.clients.add_client_success_page', ['client' => $new_client]);
    }
    
    /* Просмотр клиента : страница */
    public function view_client($client_id){
        $client = Client::find($client_id);
        $client_cars = Cars_in_service::where('owner_client_id', $client_id)->get();
        
        return view('admin.clients.view_client', ['client' => $client, 'cars' => $client_cars]);
    }
}
