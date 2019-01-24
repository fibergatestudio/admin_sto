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

        /* - Добавление в логи создание нового клиента -*/
        $create_client_log = new Clients_logs();
        $create_client_log_entry->client_id = $client_id;
        $create_client_log_entry->author_id = $author_id;

        /* - Имя клиента - */
        $client = Clients::fidn($client_id);
        $client_name = $client->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;

        $create_client_log_entry->text = 'Создание клиента - '.$client_name. 'автор - '.$author_name. 'дата - ' .date('Y-m-d');  //текст лога о создании клиента(имя), автором(имя), дата(date)
        $create_client_log_entry->save();

        // Если клиент был добавлен успешно, то предлагаем добавить машину клиента
        return view('admin.clients.add_client_success_page', ['client' => $new_client]);
    }
    
    /* Просмотр клиента : страница */
    public function view_client($client_id){
        $client = Client::find($client_id);
        $client_cars = Cars_in_service::where('owner_client_id', $client_id)->get();
        
        return view('admin.clients.view_client', ['client' => $client, 'cars' => $client_cars]);
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

        $create_client_note_log = new Clients_notes_logs();
        $create_client_note_log_entry->client_id = $client_id;  //id клиента
        $create_client_note_log_entry->author_id = $author_id;  //id автора

        /* - Имя клиента - */
        $client = Clients::find($client_id);
        $client_name = $client->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;

        $create_client_note_log_entry->text = 'Добавлено примечание к клиенту - '.$client_name. 'автор - '.$author_id. 'дата - '.date('Y-m-d');  //текст лога о добавлении заметки клинету(имя) и автором(имя), дата создания(date)
        $create_client_note_log_entry->save();  

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

        /* - Добавление в логи редактирования примечания о сотруднике - */
        $edit_client_note_log = new Clients_notes_logs();
        $edit_client_note_log_entry->client_id = $client_id;
        $edit_client_note_log_entry->author_id = $author_id;

        /* - Имя клиента - */
        $client = Clients::find($client_id);
        $client_name = $client->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;

        $edit_client_note_log_entry->text = 'Редактирование заметки по клиенту - '.$client_name.'автор - '.$author_name. 'дата - '.date('Y-m-d');  //текст лога о редактировании заметки по клиенту(имя) автором(имя), дата редактирования (date)       

        return redirect('admin/view_client/' .$client_note_entry->client_id);
    }

    
    /* Удаление примечания к клиенту */
    public function delete_client_note($note_id){
        // Удалить примечание
        Clients_notes::find($note_id)->delete();

        /* - Добавление в логи удаление клиента - */
        $delete_client_note_log = new Clients_notes_logs();
        $delete_client_note_log->client_id = $client_id;
        $delete_client_note_log->author_id = $author_id;

        /* - Имя клиента - */
        $client = Clients::find($client_id);
        $client_name = $client->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;

        $delete_client_note_log->text = 'Удалена заметка по клиенту - ' .$client_name. 'автор - '.$author_name. 'дата - '.date('Y-m-d');  //текст лога о удалении заметки по клиенту(имя) атором(имя), дата удаления (date)
        $delete_client_note_log->save();

        // И вернуться на страницу назад
        return back();
    }    
    
    /* Страница клиента : просмотр  */
    public function single_client_view($client_id){
        $client = Client::find($client_id);


        $client_cars = Cars_in_service::where('owner_client_id', $client_id)->get();

        // Информация о примечаниях клиента
        $client_notes = 
            DB::table('clients_notes')
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
