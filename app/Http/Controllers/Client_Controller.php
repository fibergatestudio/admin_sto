<?php

namespace App\Http\Controllers;
use App\Client;
use Auth;
use App\Cars_in_service;

class Client_Controller extends Controller
{
    /* Главная страница клиента */
    public function client()
    {
        $id = Auth::id();
        $owner_id = Client::where('user_id',$id)->pluck('id');
        if(!$owner_id->isEmpty()) {
            $car = Cars_in_service::where('owner_client_id', $owner_id[0])->get();
            return view('client.client')->with(array('cars' => $car));
        }
    }
}
