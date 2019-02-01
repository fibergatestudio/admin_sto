<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Records;

use Illuminate\Support\Facades\DB;

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


    public function complete_record(Request $request){

        $record_id = $request->record_id;
        $record_complete = Records::find($record_id);
        /* Получаем Айди записи */

        $complete = 'confirmed';
        $confirmed_time = $request->confirmed_time;

        $record_complete->status = $complete;
        $record_complete->confirmed_time = $confirmed_time;
        $record_complete->save();

        /* Возвращаемся обратно на страницу записей */
        return back();
    }

    public function confirmed_records_index(){

        $records = Records::all();

        // $user = Auth::user();
        // $employee_user_id = $user->id;
        // $employee = DB::table('employees')->where('user_id', $employee_user_id)->first();
        // $employee_id = $employee->id;

        $confirmed_records =
        DB::table('records')
            ->where(
                [
                    ['status', '=', 'confirmed']
                ]
            )
            ->get();

        return view('admin.assignments.confirmed_records_admin_index', 
        [
            'records' => $records,
            'confirmed_records' => $confirmed_records
        ]);
    }
}
