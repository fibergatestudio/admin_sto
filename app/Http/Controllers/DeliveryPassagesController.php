<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Delivery_passage;
use App\Employee;
use App\Employee_fine;
use App\Employees_logs;
use App\Employee_balance_logs;
use Telegram\Bot\Laravel\Facades\Telegram;
use DB;

class DeliveryPassagesController extends Controller
{

    public function index(){
        $passages = Delivery_passage::orderBy('updated_at', 'desc')->paginate(10);
        //echo '<pre>'.print_r($data_arr_assoc,true).'</pre>';
        return view('delivery_passages',['passages' => $passages]);
    }


    // Функция проверки на Json формат
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    // Функция транслитерации
    public function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }


    // Функция переработки POST данных о проходах через Сигур
    public function processing_query(Request $request){

        $data = '';

        if (isset($request)) {

            $data = $request->getContent();

            // Если это Json, то это тестовые данные, создаем ассоциативный массив и заносим данные в базу
            if($this->isJson($data)){

                $json = json_decode($data,true);
                $new_passage = new Delivery_passage();

                foreach ($json as $k => $val) {

                    if ($k == 'logs') {

                        $data_arr_assoc = [];
                        $data_arr = explode("&", $val);

                        for ($i=0; $i < count($data_arr); $i++) {
                            $temp = explode("=", $data_arr[$i]);
                            $data_arr_assoc[$temp[0]] = $temp[1];
                        }

                        $new_passage = new Delivery_passage();
                        $date = '';
                        $time = '';
                        $name1 = '';
                        $name2 = '';

                        foreach ($data_arr_assoc as $key => $value) {

                            if ($key == 'logId') {
                                $new_passage->log_id = (int)$value;
                            }
                            elseif ($key == 'date') {
                                $date = $value;
                            }
                            elseif ($key == 'time') {
                                $time = $value;
                            }
                            elseif ($key == 'name1') {
                                $name1 = $value;
                            }
                            elseif ($key == 'name2') {
                                $name2 = $value;
                            }
                            elseif ($key == 'direction') {
                                if ($value == 'вход') {
                                    $new_passage->direction = '1';
                                }
                                elseif ($value == 'выход'){
                                    $new_passage->direction = '2';
                                }
                            }
                            elseif ($key == 'tabnum') {
                                $new_passage->internal_emp_id = $value;
                            }
                            $new_passage->time = strtotime($date.' '.$time);
                            $new_passage->emp_id = $name1.' '.$name2;
                        }

                        $new_passage->save();


                        /* ----------------------Создать тестового сотрудника----------------------- */

                        $login = $this->translit($new_passage->emp_id);
                        $has_user = User::where('name', $login)->first();

                        if (!$has_user) {

                            $password = 'password';
                            $new_user = new User();
                            $new_user->name = $login;
                            $new_user->password = Hash::make($password);
                            $new_user->email = $login.'@test.com';
                            $new_user->role = 'employee';
                            $new_user->general_name = $new_passage->emp_id;
                            $new_user->save();
                            $new_user_id = $new_user->id;

                            /* Создать нового сотрудника */
                            $new_employee = new Employee();
                            $new_employee->general_name = $new_passage->emp_id;
                            $new_employee->fio = $new_passage->emp_id;
                            $new_employee->status = 'active';
                            $new_employee->date_join = date("d.m.Y");
                            /* Добавляем в таблицу работников ID соответствующего юзера */
                            $new_employee->user_id = $new_user_id;
                            $new_employee->balance = 0; //Добавление баланса
                            $new_employee->save();

                            $employee = DB::table('employees')
                            ->orderBy('id', 'desc')
                            ->first();

                            /* - Добавление в логи создание сотрудника  - */
                            $create_employee_log_entry = new Employees_logs();
                            $create_employee_log_entry->employee_id = $new_employee->id; //id сотрудника
                            $create_employee_log_entry->author_id =  Auth::user()->id;  //id автора заметки

                            /* - Имя сотрудника - */
                            $employee_id = $new_employee->id;
                            $employee = Employee::find($employee_id);
                            $employee_name = $employee->general_name;
                            /* - Имя автора - */
                            $author_id = $create_employee_log_entry->author_id;
                            $author = User::find($author_id);
                            $author_name = $author->general_name;

                            $create_employee_log_entry->text = 'Создан новый сотрудник - ' .$employee_name. ' | автор - '.$author_name;  //текст о создании сотрудника(имя) и автор(имя)
                            /* Тип? Тест */
                            $create_employee_log_entry->type = '';
                            $create_employee_log_entry->save();

                            $passage_user_id = $new_employee->id;

                        }
                        else{
                            $passage_user_id = Employee::where('user_id', $has_user->id)->first()->id;
                        }

                        /* ----------------------Конец создания тестового сотрудника----------------------- */


                        /* ----------------------Проверка на опоздание и штраф----------------------- */

                        $sum_money = 0;
                        $time_arr = [];
                        $sum_hours = 0;
                        $sum_minutes = 0;
                        $employee_id = $passage_user_id;
                        $employee = Employee::find($employee_id);
                        $employee_name = $employee->general_name;

                        //Есть ли основание для штрафа
                        if ($new_passage->direction == '1') {
                            $time_arr = explode(":", $time);
                            if ((int)$time_arr[0] >= 9) {
                                $sum_hours = (int)$time_arr[0] - 9;
                                if ((int)$time_arr[1] > 0) {
                                    $sum_minutes = (int)$time_arr[1];
                                }
                                $sum_money = $sum_hours*60 + $sum_minutes;
                            }
                        }

                        //Если есть то добавляем штраф
                        if ($sum_money > 0 AND $employee->fixed_charge) {

                            /* Найти айди рабочего */
                            $employee_balance = DB::table('employees')
                            ->where('id', '=', $passage_user_id)
                            ->first();

                            $emp_balance = $employee_balance->balance;

                            /* Добавление штрафа в режиме "ожидает применения" */
                            $new_fine = new Employee_fine;
                            $new_fine->employee_id = $passage_user_id;
                            $new_fine->amount = $sum_money;
                            $new_fine->reason = 'Опоздание на '.$sum_money.' минут '.$date.' '.$time;
                            $new_fine->old_balance = $emp_balance; // Добавления остатка
                            $new_fine->status = 'pending';
                            $new_fine->date = date('Y-m-d');
                            $new_fine->save();

                            /* - Добавляем в логи применение штрафа сотруднику - */
                            $fine_to_employee_log_entry = new Employees_logs();
                            $fine_to_employee_log_entry->employee_id = $passage_user_id;  //id сотрудника
                            $fine_to_employee_log_entry->author_id = User::where('role', 'admin')->first()->id;  //id автора

                            /* - Имя автора - */
                            $author_id = $fine_to_employee_log_entry->author_id;
                            $author = User::find($author_id);
                            $author_name = $author->general_name;

                            $fine_to_employee_log_entry->text = 'Штраф сотруднику - ' .$employee_name. ' в размере ' .$sum_money.' лей, был добавлен согласно данным о проходах в системе Sigur';
                            /* Тип? Тест */
                            $fine_to_employee_log_entry->type = '';
                            $fine_to_employee_log_entry->save();

                            /* Оповещения для телеграма */
                            $text = "<b>Произошло опоздание сотрудника!</b>\n"
                            . "<b>Сотрудник: </b>\n"
                            . "$new_passage->emp_id\n"
                            . "Опоздал на работу на\n"
                            . "$sum_money минут\n"
                            . "<b>Дата: </b>\n"
                            . "$date $time\n"
                            ;

                            Telegram::sendMessage([
                                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                                //'chat_id' => 671480169,
                                'parse_mode' => 'HTML',
                                'text' => $text
                            ]);
                        }

                        /* ----------------------Конец проверки на опоздание и штраф----------------------- */


                        /* --------- Начисление ставки за день ( в случае фикс оплаты ) и оповещение в Телеграм -------------- */

                        //Если произошел вход
                        if ($new_passage->direction == '1') {

                            //Проверяем есть ли фиксированная оплата
                            if (!$employee->fixed_charge) return $login;

                            //Проверяем если в этот день ставка уже добавлялась
                            $check_balance_logs = Employee_balance_logs::where('employee_id', $employee_id)->get();

                            foreach ($check_balance_logs as $log) {
                                if ($log->date == $date AND $log->amount == $employee->pay_per_shift) {
                                    return $log->amount;
                                }
                            }

                            $add_sum = $employee->pay_per_shift;
                            $balance = $employee->balance;

                            $new_balance = $balance + $add_sum;

                            DB::table('employees')
                            ->where('id', '=', $employee_id)
                            ->update(['balance' => $new_balance]);


                            /* Оповещения для телеграма */
                            $text = "У вас новое начисление!\n"
                            . "<b>Размер начисления: </b>\n"
                            . "$add_sum\n"
                            . "<b>Новый баланс: </b>\n"
                            . "$new_balance";

                            $chat_id = ($employee->telegram_id) ? $employee->telegram_id : env('TELEGRAM_CHANNEL_ID', '');

                            Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            //'chat_id' => 671480169,
                            'parse_mode' => 'HTML',
                            'text' => $text
                         ]);

                            // Добавить начисление в общие логи
                            $employee_balance_log = new Employee_balance_logs;
                            $employee_balance_log->amount = $add_sum;
                            $employee_balance_log->action = 'deposit';
                            $employee_balance_log->reason = 'Начисление дневной ставки при проходе';
                            $employee_balance_log->type = 'Начисление';
                            $employee_balance_log->date = $date;
                            $employee_balance_log->employee_id = $employee_id;
                            $employee_balance_log->old_balance = $balance;
                            $employee_balance_log->save();
                        }

                        /* ------ Конец начисление ставки за день ( в случае фикс оплаты ) и оповещение в Телеграм -------- */


                        return $login;
                    }
                }
            }
            // Иначе данные пришли с Сигур в виде обычной строки из которой мы создаем ассоциативный массив и заносим данные в базу
            else{
                $data_arr_assoc = [];
                $data_arr = explode("&", $data);
                $data_arr_test[] = $data;

                for ($i=0; $i < count($data_arr); $i++) {
                    $temp = explode("=", $data_arr[$i]);
                    $data_arr_assoc[$temp[0]] = $temp[1];
                }
                $new_passage = new Delivery_passage();
                $date = '';
                $time = '';
                $name1 = '';
                $name2 = '';

                foreach ($data_arr_assoc as $key => $value) {

                    if ($key == 'logId') {
                        $new_passage->log_id = (int)$value;
                    }
                    elseif ($key == 'date') {
                        $date = $value;
                    }
                    elseif ($key == 'time') {
                        $time = $value;
                    }
                    elseif ($key == 'name1') {
                        $name1 = $value;
                    }
                    elseif ($key == 'name2') {
                        $name2 = $value;
                    }
                    elseif ($key == 'direction') {
                        if ($value == 'вход') {
                            $new_passage->direction = '1';
                        }
                        elseif ($value == 'выход'){
                            $new_passage->direction = '2';
                        }
                    }
                    elseif ($key == 'tabnum') {
                        $new_passage->internal_emp_id = $value;
                    }
                    $new_passage->time = strtotime($date.' '.$time);
                    $new_passage->emp_id = $name1.' '.$name2;
                }

                $new_passage->save();


                /* ----------------------Создать тестового сотрудника----------------------- */

                $login = $this->translit($new_passage->emp_id.'-test');
                $has_user = User::where('name', $login)->first();

                if (!$has_user) {

                    $password = 'password';
                    $new_user = new User();
                    $new_user->name = $login;
                    $new_user->password = Hash::make($password);
                    $new_user->email = $login.'@test.com';
                    $new_user->role = 'employee';
                    $new_user->general_name = $new_passage->emp_id.'-test';
                    $new_user->save();
                    $new_user_id = $new_user->id;

                    /* Создать нового сотрудника */
                    $new_employee = new Employee();
                    $new_employee->general_name = $new_passage->emp_id.'-test';
                    $new_employee->fio = $new_passage->emp_id.'-test';
                    $new_employee->status = 'active';
                    $new_employee->date_join = date("d.m.Y");
                    /* Добавляем в таблицу работников ID соответствующего юзера */
                    $new_employee->user_id = $new_user_id;
                    $new_employee->balance = 0; //Добавление баланса
                    $new_employee->save();

                    $employee = DB::table('employees')
                    ->orderBy('id', 'desc')
                    ->first();

                    /* - Добавление в логи создание сотрудника  - */
                    $create_employee_log_entry = new Employees_logs();
                    $create_employee_log_entry->employee_id = $new_employee->id; //id сотрудника
                    $create_employee_log_entry->author_id =  Auth::user()->id;  //id автора заметки

                    /* - Имя сотрудника - */
                    $employee_id = $new_employee->id;
                    $employee = Employee::find($employee_id);
                    $employee_name = $employee->general_name;
                    /* - Имя автора - */
                    $author_id = $create_employee_log_entry->author_id;
                    $author = User::find($author_id);
                    $author_name = $author->general_name;

                    $create_employee_log_entry->text = 'Создан новый сотрудник - ' .$employee_name. ' | автор - '.$author_name;
                    /* Тип? Тест */
                    $create_employee_log_entry->type = '';
                    $create_employee_log_entry->save();

                    $passage_user_id = $new_employee->id;

                }
                else{
                    $passage_user_id = Employee::where('user_id', $has_user->id)->first()->id;
                }

                /* ----------------------Конец создания тестового сотрудника----------------------- */


                /* ----------------------Проверка на опоздание и штраф----------------------- */

                $sum_money = 0;
                $time_arr = [];
                $sum_hours = 0;
                $sum_minutes = 0;
                $employee_id = $passage_user_id;
                $employee = Employee::find($employee_id);
                $employee_name = $employee->general_name;

                //Есть ли основание для штрафа
                if ($new_passage->direction == '1') {
                    $time_arr = explode(":", $time);
                    if ((int)$time_arr[0] >= 9) {
                        $sum_hours = (int)$time_arr[0] - 9;
                        if ((int)$time_arr[1] > 0) {
                            $sum_minutes = (int)$time_arr[1];
                        }
                        $sum_money = $sum_hours*60 + $sum_minutes;
                    }
                }

                //Если есть основание и у работника фикс-ставка то добавляем штраф
                if ($sum_money > 0 AND $employee->fixed_charge) {

                    /* Найти айди рабочего */
                    $employee_balance = DB::table('employees')
                    ->where('id', '=', $passage_user_id)
                    ->first();

                    $emp_balance = $employee_balance->balance;

                    /* Добавление штрафа в режиме "ожидает применения" */
                    $new_fine = new Employee_fine;
                    $new_fine->employee_id = $passage_user_id;
                    $new_fine->amount = $sum_money;
                    $new_fine->reason = 'Опоздание на '.$sum_money.' минут '.$date.' '.$time;
                    $new_fine->old_balance = $emp_balance;
                    $new_fine->status = 'pending';
                    $new_fine->date = date('Y-m-d');
                    $new_fine->save();

                    /* - Добавляем в логи применение штрафа сотруднику - */
                    $fine_to_employee_log_entry = new Employees_logs();
                    $fine_to_employee_log_entry->employee_id = $passage_user_id;
                    $fine_to_employee_log_entry->author_id = User::where('role', 'admin')->first()->id;

                    /* - Имя автора - */
                    $author_id = $fine_to_employee_log_entry->author_id;
                    $author = User::find($author_id);
                    $author_name = $author->general_name;

                    $fine_to_employee_log_entry->text = 'Штраф сотруднику - ' .$employee_name. ' в размере ' .$sum_money.' лей, был добавлен согласно данным о проходах в системе Sigur';
                    /* Тип? Тест */
                    $fine_to_employee_log_entry->type = '';
                    $fine_to_employee_log_entry->save();

                    /* Оповещения для телеграма */
                    $text = "<b>Произошло опоздание сотрудника!</b>\n"
                    . "<b>Сотрудник: </b>\n"
                    . "$new_passage->emp_id\n"
                    . "Опоздал на работу на\n"
                    . "$sum_money минут\n"
                    . "<b>Дата: </b>\n"
                    . "$date $time\n"
                    ;

                    Telegram::sendMessage([
                        'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                        //'chat_id' => 671480169,
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]);
                }

                /* ----------------------Конец проверки на опоздание и штраф----------------------- */


                /* --------- Начисление ставки за день ( в случае фикс оплаты ) и оповещение в Телеграм -------------- */

                //Если произошел вход
                if ($new_passage->direction == '1') {

                    //Проверяем есть ли фиксированная оплата
                    if (!$employee->fixed_charge) return $login;

                    //Проверяем если в этот день ставка уже добавлялась
                    $check_balance_logs = Employee_balance_logs::where('employee_id', $employee_id)->get();

                    foreach ($check_balance_logs as $log) {
                        if ($log->date == $date AND $log->amount == $employee->pay_per_shift) {
                            return $log->amount;
                        }
                    }

                    $add_sum = $employee->pay_per_shift;
                    $balance = $employee->balance;

                    $new_balance = $balance + $add_sum;

                    DB::table('employees')
                    ->where('id', '=', $employee_id)
                    ->update(['balance' => $new_balance]);

                    /* Оповещения для телеграма */
                    $text = "У вас новое начисление!\n"
                    . "<b>Размер начисления: </b>\n"
                    . "$add_sum\n"
                    . "<b>Новый баланс: </b>\n"
                    . "$new_balance";

                    $chat_id = ($employee->telegram_id) ? $employee->telegram_id : env('TELEGRAM_CHANNEL_ID', '');

                    Telegram::sendMessage([
                        'chat_id' => $chat_id,
                        //'chat_id' => 671480169,
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]);

                    // Добавить начисление в общие логи
                    $employee_balance_log = new Employee_balance_logs;
                    $employee_balance_log->amount = $add_sum;
                    $employee_balance_log->action = 'deposit';
                    $employee_balance_log->reason = 'Начисление дневной ставки при проходе';
                    $employee_balance_log->type = 'Начисление';
                    $employee_balance_log->date = $date;
                    $employee_balance_log->employee_id = $employee_id;
                    $employee_balance_log->old_balance = $balance;
                    $employee_balance_log->save();
                }

                /* ------ Конец начисление ставки за день ( в случае фикс оплаты ) и оповещение в Телеграм -------- */


                return $login;
            }
        }
    }
}
