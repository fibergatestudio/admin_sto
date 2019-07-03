<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Client;
use App\Cars_in_service;
use App\Clients_notes;
use App\Clients_logs;
use App\Clients_notes_logs;
use App\Deleted_notes;
use App\Cars_in_service_logs;



class Clients_Admin_Controller extends Controller
{

    /* Перечень клиентов */
    public function clients_index(){
        $clients = Client::all();
        return view('admin.clients.clients_index', ['clients' => $clients]);
    }

    /* Добавить клиента: страница */
    public function add_client_page(){

        $clients = DB::table('clients')->get();

        return view('admin.clients.add_client_page',[
            'clients' => $clients
        ]);
    }

    /* Добавить клиента: обработка POST запроса */
    public function add_client_post(Request $request){

        //$user_id = Auth::user()->id;
        //dd($request->name);
        

        $new_client = new Client();

        $name = $request->name;
        // $surname = $request->surname;
        // $fio = $name.' '.$surname;
        $fio = $request->fio;

        $new_client->general_name = $name;
        $new_client->surname = $request->surname;
        //Новые поля в добавлении клиента
        $new_client->fio = $name;
        $new_client->organization = $request->organization;
        $new_client->phone = $request->phone;
        $new_client->referral = $request->referral;

        $new_client->save();

        /* - Добавление в логи создание нового клиента -*/
        $create_client_log_entry = new Clients_logs();
        $create_client_log_entry->client_id = $new_client->id;
        $create_client_log_entry->author_id = Auth::user()->id;


        /* - Имя клиента -*/
        $client_id = $create_client_log_entry->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $create_client_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_client_log_entry->text = 'Создание клиента - '.$client_name. ' | автор - '.$author_name;  //текст лога о создании клиента(имя), автором(имя)
        /* Тип? Тест */
        $create_client_log_entry->type = '';
        $create_client_log_entry->save();

        /* Добавление Примечания во время создания */

        if(empty($request->client_note)){

        } else {

            $create_note = new Clients_notes();
            $create_note->client_id = $new_client->id;
            $create_note->author_id = Auth::user()->id;
            $create_note->text = $request->client_note;    
            $create_note->type = '';
            $create_note->save();

        }

        // Если клиент был добавлен успешно, то предлагаем добавить машину клиента
        return view('admin.clients.add_client_success_page', ['client' => $new_client]);
    }


    /* Обновить клиента: обработка POST запроса */
    public function update_client_post(Request $request, $client_id){

        $new_client = Client::find($client_id);

        $name = $request->name;
        $surname = $request->surname;
        $fio = $name.' '.$surname;
        $new_client->general_name = $name;
        $new_client->surname = $request->surname;
        $new_client->fio = $fio;
        $new_client->organization = $request->organization;
        $new_client->phone = $request->phone;
        $new_client->balance = $request->balance;
        $new_client->discount = $request->discount;
        
        $new_client->save();

        /* - Добавление в логи Обновление клиента -*/
        $create_client_log_entry = new Clients_logs();
        $create_client_log_entry->client_id = $new_client->id;
        $create_client_log_entry->author_id = Auth::user()->id;


        /* - Имя клиента -*/
        $client_id = $create_client_log_entry->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $create_client_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_client_log_entry->text = 'Обновление клиента - '.$client_name. ' | автор - '.$author_name;  //текст лога о создании клиента(имя), автором(имя)
        /* Тип? Тест */
        $create_client_log_entry->type = '';
        $create_client_log_entry->save();

        return redirect('admin/clients/view_client/'.$client_id);
    }

    /* Просмотр клиента : страница */
    public function view_client($client_id){
        $client = Client::find($client_id);
        $client_cars = Cars_in_service::where('owner_client_id', $client_id)->get();

        //$client_notes = Client_notes::where('client_id', $client_id)->get();
        $client_notes = Client_notes::where('client_id', $client_id)->first();

        return view('admin.clients.view_client',
            [
                'client' => $client,
                'cars' => $client_cars,
                'client_notes' => $client_notes
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

        $create_client_note_log_entry = new Clients_notes_logs();
        $create_client_note_log_entry->client_id = $client->id;  //id клиента
        $create_client_note_log_entry->author_id = Auth::user()->id;  //id автора
        $create_client_note_log_entry->note_id = $new_client_note_entry->id;

        /* - Имя клиента -*/
        $client_id = $create_client_note_log_entry->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;

        /* - Имя автора - */
        $author_id = $create_client_note_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $create_client_note_log_entry->text = 'Добавлено примечание к клиенту - '.$client_name. ' | автор - '.$author_name;  //текст лога о добавлении заметки клинету(имя) и автором(имя)
        /* Тип? Тест */
        $create_client_note_log_entry->type = '';
        $create_client_note_log_entry->save();

        // И вернуться на страницу клиента
        return redirect('admin/clients/view_client/'.$client->id);
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
        $edit_client_note_log_entry = new Clients_notes_logs();
        $edit_client_note_log_entry->client_id = Clients_notes::find($request->note_id)->client_id;
        $edit_client_note_log_entry->author_id = Auth::user()->id;
        $edit_client_note_log_entry->note_id = $request->note_id;

        /* - Имя клиента - */
        $client_id = $edit_client_note_log_entry->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $edit_client_note_log_entry->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $edit_client_note_log_entry->text = 'Редактирование заметки по клиенту - '.$client_name.' | автор - '.$author_name;  //текст лога о редактировании заметки по клиенту(имя) автором(имя)
        $edit_client_note_log_entry->type = '';
        $edit_client_note_log_entry->save();

        return redirect('admin/clients/view_client/' .$client_note_entry->client_id);
    }


    /* Удаление примечания к клиенту */
    public function delete_client_note($note_id){

        $note_info = Clients_notes::find($note_id);
        /* - Добавление в логи удаление клиента - */

        $delete_client_note_log = new Deleted_notes();
        //$delete_client_note_log->client_id = Clients_notes::find($note_id)->client_id;
        $delete_client_note_log->author_id = Auth::user()->id;
        $delete_client_note_log->note_id = $note_id;

        /* - Имя клиента - */
        $client_id = $note_info->client_id;
        $client = Client::find($client_id);
        $client_name = $client->fio;
        /* - Имя автора - */
        $author_id = $delete_client_note_log->author_id;
        $author = User::find($author_id);
        $author_name = $author->general_name;

        $delete_client_note_log->text = 'Удалена заметка по клиенту - ' .$client_name. ' | автор - '.$author_name;  //текст лога о удалении заметки по клиенту(имя) атором(имя)
        /* Тип? Тест */
        $delete_client_note_log->type = '';
        $delete_client_note_log->save();

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

        $fuel_type = DB::table('fuel_type')->get();
        //dd($fuel_type);

        return view('admin.clients.view_client', [
            'client' => $client,
            'cars' => $client_cars,
            'client_notes' => $client_notes,
            'fuel_type' => $fuel_type
        ]);
    }

    public function single_client_view_edit_car(Request $request){

        $id = $request->id;
        $general_name = $request->general_name;
        $release_year = $request->release_year;
        $reg_number = $request->reg_number;
        $fuel_type = $request->fuel_type;
        $vin_number = $request->vin_number;

        DB::table('cars_in_service')->where('id', $id)
        ->update([
            'general_name' => $general_name,
            'release_year' => $release_year,
            'reg_number' => $reg_number,
            'fuel_type' => $fuel_type,
            'vin_number' => $vin_number
         ]);


        return back();
    }

    /* Живой поиск клиента*/
    public function search(Request $request){

        if($request->ajax()){
            $output = '';
            $query = $request->get('query');
            if($query != ''){
                $clients = DB::table('clients')
                    ->where('general_name', 'like', '%'.$query.'%')
                   /* ->orWhere('fio', 'like', '%'.$query.'%')
                    ->orWhere('organization', 'like', '%'.$query.'%')
                    ->orWhere('phone', 'like', '%'.$query.'%')
                    ->orWhere('balance', 'like', '%'.$query.'%')
                    ->orWhere('discount', 'like', '%'.$query.'%') */
                    ->orderBy('id', 'desc')
                    ->get();

            }else{
                $clients = DB::table('clients')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            $total_row = $clients->count();
            if($total_row > 0){
                foreach($clients as $client){
                    $output .= '
                    <tr>
                     <td><a href="view_client/'.$client->id.'">'.$client->fio.'</a></td>

                    </tr>
                    ';
                }
            }else{
               $output = '
               <tr>
                <td align="center" colspan="5">Клиент не найден</td>
               </tr>
               ';
            }
            $data = array(
            'table_data'  => $output,
            'total_data'  => $total_row
            );

            echo json_encode($data);
        }
    }
}
