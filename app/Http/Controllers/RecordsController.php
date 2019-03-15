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

        /* Время */
        // $time = '7:30';
        // for ($i = 0; $i <= 24; $i++)
        // {
        //     $next = strtotime('+30mins', strtotime($time)); // +30мин
        //     $time = date('G:i', $next); 
        //     echo "<option name=\"confirmed_time\" value=\"$time\">$time</option>";
        // }

        /* Перебираем все записи */
        foreach($records as $record){
            /* Выбираем из них unconfirmed*/
            if($record->status == 'unconfirmed'){
                
                /* Этот массив будет содержать все возможные опции для дропдауна */
                $time_for_record_array = [];

                /* Перебираем все возможные времена с шагом в полчаса */
                
                // Настройки для скрипта
                $starting_time = '09:00';
                $ending_time = '20:00';
                            
                $current_time = $starting_time;
                // брейкпойнт потом убрать из скрипта - это заглушка от бесконечного лупа
                $breakpoint = 0;
                while($this->check_time($current_time, $ending_time) && $breakpoint < 50){
                    // Проверяем доступность времени
                    $wanted_date = $record->record_date; // Дату берём из базы
                    if($this->check_time_availability($current_time, $wanted_date)){
                        $time_for_record_array[] = $current_time;
                    }
                    
                    // Увеличиваем время с шагом на полчаса
                    $current_time = $this->increase_time($current_time);
                    
                    $breakpoint += 1;
                }
                
                
                $record->available_time = $time_for_record_array;
                
            } // end if stats == unconfirmed

            
            
        } // end foreach record


        return view('admin.assignments.records_admin_index', 
        [
            'records' => $records
        ]);
    
    } // end function records_index

    /* Вспомогательная функция для records_index, которая сравнивает время*/
    /* Функция принимает время в формате 24ч, строки с нулями перед часом или минутами - 09:00 09:30 12:00 12:30 17:00 17:30 и т.д.*/
    /* И возвращает true, если время не закончилось*/
    private function check_time($current_time, $ending_time){
        // Получаем текущие часы и минуты
        $current_time_data = explode(':', $current_time);
        $current_hours = intval($current_time_data[0]);
        $current_minutes = intval($current_time_data[1]);

        // Получаем часы и минуты окончания дня
        $ending_time_data = explode(':', $ending_time);
        $ending_hours = intval($ending_time_data[0]);
        $ending_minutes = intval($ending_time_data[1]);
        
        // Сравниваем время
        if($current_hours < $ending_hours){
            // Если ещё не конец дня, то возвращаем true
            return true;
        } else {
            // Если день закончился, возвращаем false
            return false;
        }
    }

    /* Вспомогательная функция для records_index, которая увеличивает время с шагом в полчаса */
    private function increase_time($current_time){
        // Получаем текущие часы и минуты
        $current_time_data = explode(':', $current_time);
        $current_hours = intval($current_time_data[0]);
        $current_minutes = intval($current_time_data[1]);

        // Если минуты 00, то добавляем полчаса
        if($current_minutes == 0){
            $current_minutes = 30;
        } else if ($current_minutes == 30){
            // Если минуты == 30
            // То обнуляем минуты и увеличиваем час на 1
            $current_hours += 1;
            $current_minutes = 0;
        }

        // Форматируем время
        $current_hour_string = '';
        $current_minutes_string = '';
        
        // Если часы меньше 10, например 9, то записываем время как "09"
        if($current_hours < 10){
            $current_hour_string = '0'.$current_hours;
        } else {
            $current_hour_string = $current_hours;
        }

        // Если минуты == 0, то записываем их как '00'
        
        if($current_minutes == 0){
            $current_minutes_string = '00';
        } else {
            $current_minutes_string = '30';
        }

        // Объединяем время и возвращаем его
        $current_time = $current_hour_string.':'.$current_minutes_string;
        return $current_time;

    }

    /* Вспомогательная функция для records_index, которая проверяет доступность времени на дату и время */
    private function check_time_availability($wanted_time, $wanted_date){
        // Форматируем время
        $wanted_time_data = explode(':', $wanted_time);
        $wanted_hour = intval($wanted_time_data[0]);
        $wanted_minutes = $wanted_time_data[1];
        $wanted_time_formatted = $wanted_hour.':'.$wanted_minutes;
        

        // Запрашиваем в базе, есть ли уже запись на эту дату + это время; если есть count будет = 1, если нет count = 0
        $count_checker = 
            DB::table('records')
                ->where([
                    ['record_date', '=', $wanted_date], // Проверка даты
                    ['confirmed_time', '=', $wanted_time_formatted]  // Проверка времени
                ])
                ->count();
        
        if($count_checker == 0){
            return true;
        } else {
            return false;
        }
        

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

    public function delete_record(Request $request){

        $record_id = $request->record_id;

        Records::find($record_id)->delete();

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
