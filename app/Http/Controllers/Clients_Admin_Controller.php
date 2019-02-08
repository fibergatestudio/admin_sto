<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Client;
use App\Cars_in_service;
use App\Clients_notes;

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
        
        return view('admin.clients.view_client',
            [
                'client' => $client, 
                'cars' => $client_cars
            ]);
    }


    /**/
    /* Добавление примечания к клиенту : страница */
    public function add_note_to_client_page($client_id){
        $client = Client::find($client_id);
        return view('admin.clients.add_note_to_client', ['client' => $client]);  
    }

    /* Добавление примечания к клиенту : POST */
    public function add_note_to_client_post(Request $request){
        // Добавить примечание
        $client = Client::find($request->client_id);
        $new_client_note_entry = new Clients_notes();
        $new_client_note_entry->client_id = $client->id;
        $new_client_note_entry->author_id = Auth::user()->id;
        $new_client_note_entry->text = $request->note_content;
        $new_client_note_entry->type = 'note';
        $new_client_note_entry->save();

        // И вернуться на страницу клиента
        return redirect('admin/view_client/'.$client->id);
    }

    /* - Редактирование примечания о клиенте : страница - */
    public function edit_client_note($note_id){
        $client_note = Clients_notes::find($note_id);
        return view('admin.clients.edit_client_note', compact(['note_id', 'client_note']));
    }  

    /* - Редактирование примечания о клиенте : POST - */
    public function edit_client_note_post(Request $request){
        $client_note_entry = Clients_notes::find($request->note_id);
        $client_note_entry->text = $request->text;
        $client_note_entry->save();

        return redirect('admin/view_client/' .$client_note_entry->client_id);
    }

    
    /* Удаление примечания к клиенту */
    public function delete_client_note($note_id){
        // Удалить примечание
        Clients_notes::find($note_id)->delete();
        // И вернуться на страницу назад
        return back();
    }    
    /**/
    /* Страница клиента : просмотр  */
    public function single_client_view($client_id){
        $client = Client::find($client_id);


        $client_cars = Cars_in_service::where('owner_client_id', $client_id)->get();

        // Информация о примечаниях клиента
        $client_notes = 
            DB::table('clients_notes')
                ->where('client_id', $client_id)
                ->get();
    

         //Получаем имя автора
        foreach($client_notes as $client_note){
            $client_note->author_name = User::find($client_note->author_id)->general_name;
        }

        return view('admin.clients.view_client', [
            'client' => $client,
            'cars' => $client_cars,
            'client_notes' => $client_notes
        ]);
    }   
}
