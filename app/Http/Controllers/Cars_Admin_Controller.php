<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Shift;
use App\Car_model_list;

class Cars_Admin_Controller extends Controller
{
    /* Главная страница с моделями */
    public function cars_index(){

        $car_models = 
            DB::table('car_model_list')
                ->get();

        // Выводим
        return view('admin.cars.cars_index', ['car_models' => $car_models]);
    }
}
