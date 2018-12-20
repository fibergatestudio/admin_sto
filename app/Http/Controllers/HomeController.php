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
        if(Auth::user()->isAdmin()){
            return redirect('view_employees');
        } else if (Auth::user()->isEmployee()) {
            return redirect('employee/dashboard');
        }
        

        /* Если пользователь администратор - отправляем его на админскую панель */
        // ...

        /* Если пользователь - сотрудник, то отправляем его на панель сотрудника */
        // ...
    }
}
