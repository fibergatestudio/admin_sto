<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return redirect('view_employees');

        /* Если пользователь администратор - отправляем его на админскую панель */
        // ...

        /* Если пользователь - сотрудник, то отправляем его на панель сотрудника */
        // ...
    }
}
