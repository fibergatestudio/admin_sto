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

        $car_models =  DB::table('car_model_list')->paginate(20);

        // Выводим
        return view('admin.cars.cars_index', ['car_models' => $car_models]);
    }

    public function add_car_entry(Request $request){

        $new_car_entry = new Car_model_list();
        $new_car_entry->general_name = $request->general_name; /* ... */
        $new_car_entry->brand = $request->brand; /* ... */
        $new_car_entry->model = $request->model; /* ... */
        $new_car_entry->save();

        return back();
    }

    public function edit_car_entry($car_entry_id){
        
        $single_model = Car_model_list::find($car_entry_id);

        $car_model = DB::table('car_model_list')->where('id', $car_entry_id)->first();

        return view('admin.cars.car_edit', ['single_model' =>  $single_model, 'car_model' => $car_model]);

    }
    public function submit_car_entry(Request $request){
        
        $car_entry_id = $request->id;
        $new_general_name = $request->new_general_name;
        $new_brand = $request->new_brand;
        $new_model = $request->new_model;

        $car_entry = Car_model_list::find($car_entry_id);
        $car_entry->general_name = $new_general_name;
        $car_entry->brand = $new_brand;
        $car_entry->model = $new_model;
        $car_entry->save();
        
        /* Возвращаемся на страницу */
        return back();

     

    }

    public function delete_car_entry($car_entry_id){
        
        Car_model_list::find($car_entry_id)->delete();
        return back();
    }
}
