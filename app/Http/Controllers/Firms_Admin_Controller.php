<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Firms;

class Firms_Admin_Controller extends Controller
{

    //Перечень фирм
    public function firms_index(){

        $firms = Firms::all();
        return view('admin.firms.firms_index', ['firms' => $firms]);
    }

    public function add_firm(Request $request){

        $new_firm = new Firms();
        $new_firm->firm_name = $request->firm_name;
        $new_firm->firm_discount = $request->firm_discount;
        $new_firm->save();

        return back();
    }

}
