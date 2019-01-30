<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Records;

class RecordsController extends Controller
{
    /* Вывод вьюхи */
    public function records_index(){

        $records = Records::all();

        return view('admin.assignments.records_admin_index', 
        [
            'records' => $records
        ]);
    }


    /* Добавление данных записи в базу */
    public function add_record(Request $request){

        $new_record = new Records();
        $new_record->status = $request->status;
        $new_record->name = $request->name;
        $new_record->car_year = $request->car_year;
        $new_record->car_brand = $request->car_brand;
        $new_record->car_model = $request->car_model;
        $new_record->car_number = $request->car_number;
        $new_record->record_date = $request->record_date ;
        $new_record->phone = $request->phone;
        $new_record->save();

        return back();
    }

    /* Подтверждение записи */
    public function complete_record($record_id){


        $complete = 'confirmed';

        $record_complete = Records::find($record_id);
        $record_complete->status = $complete;
        $record_complete->save();

        /* Возвращаемся обратно на страницу записей */
        return back();
    }
}
