<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('home');
        
        /* Если пользователь администратор - отправляем его на админскую панель */
        if(Auth::user()->isAdmin()){
            return redirect('supervisor/view_employees');
        } else if (Auth::user()->isEmployee()) {
            /* Если пользователь - сотрудник, то отправляем его на панель сотрудника */
            return redirect('employee/dashboard');
        } else if (Auth::user()->isSupplyOfficer()){
            /* Если пользователь - снабженец, то отправляем его на панель снабженца */
            return redirect('supply_officer/index');
        }
        else if (Auth::user()->isClient()){
            /* Если пользователь - клиент, то отправляем его на панель клиента */
            return redirect('/client');
        }
        else if (Auth::user()->isMaster()){
            /* Если пользователь - мастер, то отправляем его на панель мастера */
            return redirect('/master');
        }

    }
}
