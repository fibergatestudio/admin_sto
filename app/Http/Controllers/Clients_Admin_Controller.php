<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        
        return view('admin.clients.view_client', ['client' => $client, 'cars' => $client_cars]);
    }


    /**/
    /* Добавление примечания к клиенту : страница */
    public function add_note_to_client_page($client_id){
        $client = Client::find($client_id);
        return view('admin.clients.add_note_to_client', ['client' => $client]);  //Уточнить
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
    /* Удаление примечания к клиенту */
    public function delete_client_note($note_id){
        // Удалить примечание
        Client_notes::find($note_id)->delete();
        // И вернуться на страницу назад
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
                    ->orWhere('fio', 'like', '%'.$query.'%')
                    ->orWhere('organization', 'like', '%'.$query.'%')
                    ->orWhere('phone', 'like', '%'.$query.'%')
                    ->orWhere('balance', 'like', '%'.$query.'%')
                    ->orWhere('discount', 'like', '%'.$query.'%')
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
                     <td><a href="view_client/'.$client->id.'">'.$client->general_name.'</a></td>
                     <td>'.$client->fio.'</td>
                     <td>'.$client->organization.'</td>
                     <td>'.$client->phone.'</td>
                     <td>'.$client->balance.'</td>
                     <td>'.$client->discount.'</td>
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
