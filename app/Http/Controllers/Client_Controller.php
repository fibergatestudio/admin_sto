<?php

namespace App\Http\Controllers;
use Auth;
use App\Cars_in_service;

class Client_Controller extends Controller
{
    /* Главная страница клиента */
    public function client()
    {
        $id = Auth::id();
        $car = Cars_in_service::where('owner_client_id',$id)->get();
        return view('client.client')->with(array('cars'=>$car));
    }
}
