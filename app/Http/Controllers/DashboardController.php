<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard_index(){
        return view('admin.dashboard_admin');
    }
}
