<?php
/*
* Контроллер админ версии нарядов
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Employee;
use App\Client;
use App\Cars_in_service;
use App\Assignment;
use App\Sub_assignment;
use App\Workzone;
use Telegram\Bot\Laravel\Facades\Telegram;

use App\Assignments_income;
use App\Assignments_expense;
use App\Assignments_completed_works;

use App\Zonal_assignments_income;
use App\Zonal_assignments_expense;
use App\Zonal_assignments_completed_works;

use DateTime;
use App\Exchange_rates;

use App\Month_profitability;

class Assignments_Admin_Controller extends Controller
{
    
    /* Отображения списка всех нарядов */
    public function assignments_index(){
        /* Получаем всю нужную информацию по нарядам */
        $assignments_data =
            DB::table('assignments')
                ->join('employees', 'assignments.responsible_employee_id', '=', 'employees.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                //->join('clients', 'assignments.car_id', '=', 'clients.id')
                ->join('workzones', 'cars_in_service.workzone', '=', 'workzones.id') // Джоин рабочей зоны
                ->orderBy('order','ASC')
                ->select(
                        'assignments.*',
                        'employees.general_name AS employee_name',
                        'cars_in_service.general_name AS car_name',
                        'cars_in_service.vin_number AS vin_number',
                        'cars_in_service.release_year AS release_year',
                        'cars_in_service.reg_number AS reg_number',
                        'cars_in_service.car_color AS car_color',
                        'cars_in_service.workzone AS workzone',
                        'workzones.general_name as workzone_name', //Имя рабочей зоны
                        'workzones.workzone_color as workzone_color' //Цвет рабчоей зоны
                        //'clients.phone AS clients_phone',
                        //'clients.fio AS clients_fio'
                    )
                ->get();

        return view('assignments_admin.assignments_admin_index', ['assignments' => $assignments_data]);
    }

    /* Добавления наряда: страница с формой */
    public function add_assignment_page($car_id){
        
        $client = Client::get_client_by_car_id($car_id);
        
        /* Собираем информация по сотрудникам, которых можно указать как ответственных */
        $employees = Employee::where('status', 'active')->get();

        /* Информация о машине */
        $car = Cars_in_service::find($car_id);
        
        return view(
            'admin.assignments.add_assignment_page',
            [
                'employees' => $employees,
                'owner' => $client,
                'car' => $car
            ]
        );
    }
    
    /* Добавления наряда: страница обработки POST данных*/
    public function add_assignment_page_post(Request $request){
        /* Получаем данные из запроса */
        $responsible_employee_id = $request->responsible_employee_id;
        $assignment_description = $request->assignment_description;
        $car_id = $request->car_id;

        /* Информация о клиенте и его машине для телеграма */
        $client = Client::get_client_by_car_id($car_id);
        $car = Cars_in_service::find($car_id);

        /* Создаём новый наряд и сохраняем его*/
        $new_assignment = new Assignment();
        $new_assignment->responsible_employee_id = $responsible_employee_id;
        $new_assignment->description = $assignment_description;
        $new_assignment->car_id = $car_id;
        $new_assignment->date_of_creation = date('Y-m-d');
        $new_assignment->status = 'active';
        /* TEST confirmed */
        $new_assignment->confirmed = 'unconfirmed';
        /* end TEST confirmed */
        $new_assignment->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_assignment_notification == 1){

            /* Оповещения для телеграма */
            $text = "У вас новый наряд!\n"
            . "<b>Клиент: </b>\n"
            . "$client->fio\n"
            . "<b>Авто: </b>\n"
            . "$car->general_name\n"
            . "<b>Дата: </b>\n"
            . "$new_assignment->date_of_creation\n"
            . "<b>Описание: </b>\n"
            .  $assignment_description;

            Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
            ]);

        } else {

        }
        

        /* Возвращаемся на страницу нарядов по авто */
        return redirect('admin/cars_in_service/view/'.$car_id);
    }

    /* Просмотр наряда : страница */
    public function view_assignment($assignment_id){
        /* Получаем информацию по наряду */
        $assignment = Assignment::find($assignment_id);

        /* Получаем дополнительную информацию по нарядам */
        /* Имя клиента */
        $assignment->client_name = $assignment->get_client_name();
        /* Авто */
        $assignment->car_name = $assignment->get_car_name();
        /* Год авто */
        $assignment->car_year = $assignment->get_car_year();
        /* Рег. номер авто */
        $assignment->car_reg_number = $assignment->get_car_reg_number();

                /* Доход/расход/работы */
                /* Получаем доходную часть */
                // $assignment_income = Assignments_income::where('assignment_id', $assignment_id)->get();
                $assignment_income = Assignments_income::where('assignment_id', $assignment_id)
                ->join('assignments', 'assignments_income.assignment_id', '=', 'assignments.id')
                ->join('cars_in_service', 'assignments.car_id', '=', 'cars_in_service.id')
                ->join('clients', 'cars_in_service.owner_client_id', '=', 'clients.id')
                ->orderBy('order','ASC')
                ->select(
                        'assignments_income.*',
                        'assignments.id AS assignment_id',
                        'assignments.car_id AS assignment_car_id',
                        'cars_in_service.general_name AS assignment_car_name',
                        'cars_in_service.release_year AS assignment_release_year',
                        'cars_in_service.reg_number AS assignment_reg_number',
                        'clients.general_name AS assignment_client_name'
                    )
                ->get();

                /* Получаем зональную доходную часть */
                $zonal_assignment_income = Sub_assignment::where('assignment_id', $assignment_id)
                ->join('zonal_assignments_income', 'sub_assignments.id', '=', 'zonal_assignments_income.sub_assignment_id')
                ->join('assignments', 'sub_assignments.assignment_id', '=', 'assignments.id')
                ->orderBy('order','ASC')
                ->select(
                    'sub_assignments.*',
                    'sub_assignments.id AS sub_assignment_id',
                    'zonal_assignments_income.zonal_amount AS sub_as_amount',
                    'assignments.id AS assignment_id'
                )
                ->get();

                //dd($zonal_assignment_income);



                /* Получаем расходную часть */
                $assignment_expense = Assignments_expense::where('assignment_id', $assignment_id)->get();
                /* Получаем выполненые работы */
                $assignment_work = Assignments_completed_works::where('assignment_id', $assignment_id)->get();

        /* Получаем список зональных нарядов */
        $sub_assignments = 
            DB::table('sub_assignments')
            ->where('assignment_id', $assignment_id)
            ->join('workzones', 'sub_assignments.workzone_id', '=', 'workzones.id')
            ->orderBy('order','ASC')
            ->select('sub_assignments.*', 'workzones.general_name')
            ->get();


        /* Собираем дополнительные данные по зональным нарядам */
        foreach($sub_assignments as $sub_assignment){
            /* Название рабочей зоны */
            $sub_assignment->workzone_name = Workzone::find($sub_assignment->workzone_id)->general_name;
            
            /* Имя ответственного работника */
            $sub_assignment->responsible_employee = Employee::find($sub_assignment->responsible_employee_id)->general_name;
        }

        /* Получаем список картинок по наряду */
        $images = [];
        foreach(Storage::files('public/'.$assignment_id) as $file){
             $images[] = $file;
        }

        /* Получаем список картинок по наряду */
        $accepted_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/accepted') as $file){
             $accepted_images[] = $file;
        }

        /* Получаем список картинок по наряду */
        $repair_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/repair') as $file){
             $repair_images[] = $file;
        }
        
        /* Получаем список картинок по наряду */
        $finished_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/finished') as $file){
             $finished_images[] = $file;
        }

        // .. Собираем информацию по зональным нарядам

        $sub_assignment_id = [];

        /* Получаем массив id зональных нарядов */
        $sub_assignment_ids = DB::table('sub_assignments')->where('assignment_id', $assignment_id)->pluck('id');
        foreach ($sub_assignment_ids as $value) {
            $sub_assignment_id[] = $value;
        }
        //echo '<pre>'.print_r($sub_assignment_id,true).'</pre>';
        /* Получаем доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::whereIn('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::whereIn('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }


        /* Возвращаем представление */
        return view('admin.assignments.view_assignment_page',
            [
                'assignment' => $assignment,
                'sub_assignments' => $sub_assignments,
                'image_urls'=> $images,
                'accepted_image_urls'=> $accepted_images,
                'repair_image_urls'=> $repair_images,
                'finished_image_urls'=> $finished_images,
                'assignment_income' => $assignment_income,
                'zonal_assignment_income' => $zonal_assignment_income,
                'assignment_expense' => $assignment_expense,
                'zonal_assignment_income' => $zonal_assignment_income, 
                'zonal_assignment_expense' => $zonal_assignment_expense, 
                'assignment_work' => $assignment_work,
                'usd' => $usd,
                'eur' => $eur       
            ]);
    }


    /* Обновления позации элемента таблицы */
    public function updateOrder(Request $request){

        $sub_assignments = Sub_assignment::all();

        foreach ($sub_assignments as $sub_assignment) {
            $sub_assignment->timestamps = false; // To disable update_at field updation
            $id = $sub_assignment->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $sub_assignment->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }

    /* Обновления позации элемента таблицы */
    public function updateMainOrder(Request $request){

        $assignments = Assignment::all();

        foreach ($assignments as $assignment) {
            $assignment->timestamps = false; // To disable update_at field updation
            $id = $assignment->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $assignment->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }


    /* Изменение названия наряда */
    public function change_assignment_name(Request $request){
        /* Меняем название наряда */
        $assignment_id = $request->assignment_id;
        $new_name = $request->new_name;

        $assignment = Assignment::find($assignment_id);
        $assignment->description = $new_name;
        $assignment->save();
        
        /* Возвращаемся на страницу наряда */
        return back();
    }

    /* Добавление зонального наряда : страница */
    public function add_sub_assignment_page($assignment_id){
        /* Получаем данные по основному наряду */
        $assignment = Assignment::find($assignment_id);

        /* Данные по рабочим зонам */
        $workzones = Workzone::all();

        /* Данные по сотрудникам, которых можно сделать ответственными */
        $employees = Employee::all();

        /* Возвращаем страницу добавления зонального наряда */
        return view('admin.assignments.add_sub_assignment_page', [
            'assignment' => $assignment, // "Родительский" наряд
            'workzones' => $workzones, //  Рабочие зоны
            'employees' => $employees // Сотрудники
        ]);
    }

    /* Добавление зонального наряда : POST */
    public function add_sub_assignment_post(Request $request){
        
        /* Получаем данные из запроса */
        $main_assignment_id = $request->assignment_id; // ID "родительского" наряда
        $sub_assignment_name = $request->name; // Название зонального наряда
        $sub_assignment_description = $request->description; // Описание зонального наряда
        $workzone_id = $request->workzone; // ID рабочей зоны
        $responsible_employee_id = $request->responsible_employee; // ID ответственного лица (employee.id)

        $start_time = $request->start_time;
        $end_time = $request->end_time;
        
        /* Создание нового зонального наряда */
        $sub_assignment = new Sub_assignment();
        $sub_assignment->assignment_id = $main_assignment_id;
        $sub_assignment->name = $sub_assignment_name;
        $sub_assignment->description = $sub_assignment_description;
        $sub_assignment->workzone_id = $workzone_id;
        $sub_assignment->responsible_employee_id = $responsible_employee_id;
        $sub_assignment->date_of_creation = date('Y-m-d');
        $sub_assignment->start_time = $start_time;
        $sub_assignment->end_time = $end_time;
        $sub_assignment->save();

        /* Возвращаемся на страницу */
        return redirect('/admin/assignments/view/'.$main_assignment_id);
    }

    /* Добавление фотографий к наряду : Страница */
    public function add_photo_to_assignment_page($assignment_id){
        /* Получаем текущий наряд, к которому будут добавляться фото */
        $assignment = Assignment::find($assignment_id);
        
        /* Отображаем представление */
        return view('admin.assignments.add_photo_to_assignment_page', ['assignment' => $assignment]);
    }

    /* Добавление фотографий к наряду : Обработка запроса */
    public function add_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->test->store('public/'.$assignment_id);
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий принятой машины к наряду : Обработка запроса */
    public function add_accepted_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->accepted_photo->store('public/'.$assignment_id.'/accepted');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий процесса ремонта к наряду : Обработка запроса */
    public function add_repair_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->repair_photo->store('public/'.$assignment_id.'/repair');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Добавление фотографий готовой машины к наряду : Обработка запроса */
    public function add_finished_photo_to_assignment_post(Request $request){
        /* Получаем данные из запроса */
        /* ID наряда, к которому добавляем фото */
        $assignment_id = $request->assignment_id;

        /* Сохраняем фото */
        $request->finished_photo->store('public/'.$assignment_id.'/finished');
        
        /* Возвращаемся на страницу авто */
        return redirect('admin/assignments/view/'.$assignment_id);
    }

    /* Удаление фотографий : страница */
    public function delete_photos_page($assignment_id){
        /* Получить список фотографий по наряду */
        $images = [];
        foreach(Storage::files('public/'.$assignment_id) as $file){
             $images[] = $file;
        }

        /* Получаем список фото принятых машин по наряду */
        $accepted_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/accepted') as $file){
                $accepted_images[] = $file;
        }

        /* Получаем список фото процесса ремонта по наряду */
        $repair_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/repair') as $file){
                $repair_images[] = $file;
        }
        
        /* Получаем список фото выдачи готовых машин по наряду */
        $finished_images = [];
        foreach(Storage::files('public/'.$assignment_id.'/finished') as $file){
                $finished_images[] = $file;
        }
        
        /* Вывести страницу */
        return view('admin.assignments.delete_photos_from_assignment_page', 
        [
            'images' => $images,
            'accepted_image_urls'=> $accepted_images,
            'repair_image_urls'=> $repair_images,
            'finished_image_urls'=> $finished_images,
            'assignment_id' => $assignment_id
            ]);
    }

    /* Удаление фотографий : POST */
    public function delete_photos_post(Request $request){
        /* Удалить фото */
        Storage::delete($request->path_to_file);
        
        /* Вернуться на страницу удаления фотографий */
        return redirect('admin/assignments/'.$request->assignment_id.'/delete_photos_page');
    }

    public function print_settings_page(){



        return view('admin.assignments.print_settings_page');
    }

    public function assignment_management($sub_assignment_id){

        $sub_assignment = Sub_assignment::find($sub_assignment_id); 
        $assignment = Assignment::find($sub_assignment->assignment_id); 
        
        // .. Собираем информацию по наряду
        
        /* Получаем доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::where('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::where('sub_assignment_id', $sub_assignment_id)->get();
        /* Получаем выполненые работы */
        $zonal_assignment_work = Zonal_assignments_completed_works::where('sub_assignment_id', $sub_assignment_id)->get();
    
        return view('admin.assignments.assignment_management',
        [
            'assignment' =>  $assignment,
            'sub_assignment' => $sub_assignment,
            'zonal_assignment_income' => $zonal_assignment_income, 
            'zonal_assignment_expense' => $zonal_assignment_expense, 
            'zonal_assignment_work' => $zonal_assignment_work
        ]);
    }

        /* Добавить заход денег : POST */
        public function add_income_post(Request $request){
            /* Создаём новое вхождение по заходу денег и вносим туда информацию */
            $new_income_entry = new Assignments_income();
            $new_income_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_income_entry->amount = $request->amount; /* Сумма захода */
            $new_income_entry->currency = $request->currency; /* Валюта захода */
            $new_income_entry->basis = $request->basis; /* Основание для захода денег */
            $new_income_entry->description = $request->description; /* Описание для захода */
            $new_income_entry->save();


            /* Проверка оповещенияй (включено ли) */
            $user_id = Auth::user()->id;
            $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();
    
            if($notification_check->tg_income_notification == 1){
    
                /* Оповещения для телеграма */
                $text = "У вас новый доход!\n"
                . "<b>ID Наряда: </b>\n"
                . "$new_income_entry->assignment_id\n"
                . "<b>Сумма Дохода: </b>\n"
                . "$new_income_entry->amount $new_income_entry->currency\n"
                . "<b>Основание: </b>\n"
                . "$new_income_entry->basis\n"
                . "<b>Описание: </b>\n"
                .  $new_income_entry->description;
    
                Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
                ]);
    
            } else {
    
            }
    
            
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
        /* Добавить расход денег : POST */
        public function add_expense_post(Request $request){
            /* Создаём новое вхождение по расходу денег и вносим туда информацию */
            $new_expense_entry = new Assignments_expense();
            $new_expense_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_expense_entry->amount = $request->amount; /* Сумма расхода */
            $new_expense_entry->currency = $request->currency; /* Валюта расхода */
            $new_expense_entry->basis = $request->basis; /* Основание для расхода денег */
            $new_expense_entry->description = $request->description; /* Описание для расхода */
            $new_expense_entry->save();

            /* Проверка оповещенияй (включено ли) */
            $user_id = Auth::user()->id;
            $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

            if($notification_check->tg_expense_notification == 1){

                /* Оповещения для телеграма */
                $text = "У вас новый расход!\n"
                . "<b>ID Наряда: </b>\n"
                . "$new_expense_entry->assignment_id\n"
                . "<b>Сумма Расхода: </b>\n"
                . "$new_expense_entry->amount $new_expense_entry->currency\n"
                . "<b>Основание: </b>\n"
                . "$new_expense_entry->basis\n"
                . "<b>Описание: </b>\n"
                .  $new_expense_entry->description;

                Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
                ]);

            } else {

            }
    
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
        /* Добавить выполненые работы : POST */
        public function add_works_post(Request $request){
            /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
            $new_works_entry = new Assignments_completed_works();
            $new_works_entry->assignment_id = $request->assignment_id; /* Идентификатор наряда */
            $new_works_entry->basis = $request->basis; 
            $new_works_entry->description = $request->description; 
            $new_works_entry->status = 'unconfirmed';
            $new_works_entry->save();
    
    
            /* Возвращаемся обратно на страницу наряда */
            return back();
        }
    /* Добавить зональный заход денег : POST */
    public function add_zonal_assignment_income(Request $request){
        /* Создаём новое вхождение по заходу денег и вносим туда информацию */
        $new_zonal_income_entry = new Zonal_assignments_income();
        $new_zonal_income_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор зонального наряда  */
        $new_zonal_income_entry->zonal_amount = $request->zonal_amount; /* Сумма захода */
        $new_zonal_income_entry->zonal_currency = $request->currency; /* Валюта захода */
        $new_zonal_income_entry->zonal_basis = $request->zonal_basis; /* Основание для захода денег */
        $new_zonal_income_entry->zonal_description = $request->zonal_description; /* Описание для захода */
        $new_zonal_income_entry->save();

         /* Проверка оповещенияй (включено ли) */
         $user_id = Auth::user()->id;
         $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();
 
         if($notification_check->tg_income_notification == 1){
 
             /* Оповещения для телеграма */
             $text = "У вас новый зональный доход!\n"
             . "<b>ID Наряда: </b>\n"
             . "$new_zonal_income_entry->sub_assignment_id\n"
             . "<b>Сумма Дохода: </b>\n"
             . "$new_zonal_income_entry->zonal_amount $new_zonal_income_entry->zonal_currency\n"
             . "<b>Основание: </b>\n"
             . "$new_zonal_income_entry->zonal_basis\n"
             . "<b>Описание: </b>\n"
             .  $new_zonal_income_entry->zonal_description;
 
             Telegram::sendMessage([
             'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
             'parse_mode' => 'HTML',
             'text' => $text
             ]);
 
         } else {
 
         }

        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить зональный расход денег : POST */
    public function add_zonal_assignment_expense(Request $request){
        /* Создаём новое вхождение по расходу денег и вносим туда информацию */
        $new_zonal_expense_entry = new Zonal_assignments_expense();
        $new_zonal_expense_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор зонального наряда */
        $new_zonal_expense_entry->zonal_amount = $request->zonal_amount; /* Сумма расхода */
        $new_zonal_expense_entry->zonal_currency = $request->currency; /* Валюта расхода */
        $new_zonal_expense_entry->zonal_basis = $request->zonal_basis; /* Основание для расхода денег */
        $new_zonal_expense_entry->zonal_description = $request->zonal_description; /* Описание для расхода */
        $new_zonal_expense_entry->save();

        /* Проверка оповещенияй (включено ли) */
        $user_id = Auth::user()->id;
        $notification_check = DB::table('user_options')->where('id','=', $user_id)->first();

        if($notification_check->tg_expense_notification == 1){

            /* Оповещения для телеграма */
            $text = "У вас новый зональный расход!\n"
            . "<b>ID Наряда: </b>\n"
            . "$new_zonal_expense_entry->sub_assignment_id\n"
            . "<b>Сумма Расхода: </b>\n"
            . "$new_zonal_expense_entry->zonal_amount $new_zonal_expense_entry->zonal_currency\n"
            . "<b>Основание: </b>\n"
            . "$new_zonal_expense_entry->zonal_basis\n"
            . "<b>Описание: </b>\n"
            .  $new_zonal_expense_entry->zonal_description;

            Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'parse_mode' => 'HTML',
            'text' => $text
            ]);

        } else {

        }


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }
    /* Добавить зональные выполненые работы : POST */
    public function add_zonal_assignment_works(Request $request){
        /* Создаём новое вхождение по выполненым работам и вносим туда информацию */
        $new_zonal_works_entry = new Zonal_assignments_completed_works();
        $new_zonal_works_entry->sub_assignment_id = $request->sub_assignment_id; /* Идентификатор наряда */
        $new_zonal_works_entry->zonal_basis = $request->zonal_basis; /* Основание для расхода денег */
        $new_zonal_works_entry->zonal_description = $request->zonal_description; /* Описание для расхода */
        $new_zonal_works_entry->save();


        /* Возвращаемся обратно на страницу наряда */
        return back();
    }

    public function update_zonal_assignment_time(Request $request){

        $sub_assignment_id = $request->assignment_id;
        $new_start_time = $request->new_start_time;
        $new_end_time = $request->new_end_time;

        // $time = DB::table('sub_assignments')->where('id', $sub_assignment_id)

        DB::table('sub_assignments')
        ->where('id', '=', $sub_assignment_id)
        ->update([
            'start_time' => $new_start_time,
            'end_time' => $new_end_time
            ]);

        return back();
    }

    /* Отображения общей рентабельности */
    public function profitability_index(){

        // ** Получаем валюту **//

        // Конверт. 1 USD в MDL

        $exchange_rates = DB::table('exchange_rates')->first();

        //$usd_to_mdl = $rates->exchange('USD', 1, 'MDL');

        // Конверт. 1 EUR в MDL
        //$eur_to_mdl = $rates->exchange('EUR', 1, 'MDL');


        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();

        return view('assignments_admin.profitability_admin_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'profitability_months' => $profitability_months,
            'exchange_rates' => $exchange_rates
        ]);
    }

    /* Курс валют */
    public function add_exchange_rates(Request $request){
        /* Устанавливаем курс валют */        
        if (DB::table('exchange_rates')->select('usd')->get()->count() > 0) {
                DB::table('exchange_rates')
                ->update(['usd' => $request->usd_currency, 'eur' => $request->eur_currency]);
            }
            else{
                DB::table('exchange_rates')
                ->insert(['usd' => $request->usd_currency, 'eur' => $request->eur_currency]);
            }

            return back();
    }

    /* Отображение месячной рентабельности*/
    public function profitability_month_index(){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        $rental_price = 0;
        $electricity = 0;
        $water_supply = 0;
        $gas = 0;
        $cleaning = 0;
        $garbage_removal = 0;
        $other_expenses = 0;
        $date = date("Y-m-d");
        
        /* Получаем последнюю запись в таблице расходов */
        $month_profitability = Month_profitability::latest()->first();

        if ($month_profitability) {
            $rental_price = $month_profitability->rental_price;
            $electricity = $month_profitability->electricity;
            $water_supply = $month_profitability->water_supply;
            $gas = $month_profitability->gas;
            $cleaning = $month_profitability->cleaning;
            $garbage_removal = $month_profitability->garbage_removal;
            $other_expenses = $month_profitability->other_expenses;
            $date = $month_profitability->date;
        }        

        $start_date = $date;
        $end_date = $date;
        $date_arr[] = substr($date,0,-3);

        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }

    /* Отображение месячной рентабельности с заданной датой*/
    public function profitability_month_show($start_date, $end_date){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }

        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();

        /* Получаем записи в таблице расходов за определенный период*/

        $rental_price = 0;
        $electricity = 0;
        $water_supply = 0;
        $gas = 0;
        $cleaning = 0;
        $garbage_removal = 0;
        $other_expenses = 0;
        $date_arr = [];
        $start_date_new = substr($start_date,0,-3).'-01';
        $end_date_new = substr($end_date,0,-3).'-01';

        /* Если у нас даты включают один месяц*/
        if (substr($start_date,0,-3) === substr($end_date,0,-3)) {
            foreach($profitability_months as $value) {
                if (substr($end_date,0,-3) === substr($value->date,0,-3)) {
                    $month_profitability = Month_profitability::find($value->id);

                    $rental_price = $month_profitability->rental_price;
                    $electricity = $month_profitability->electricity;
                    $water_supply = $month_profitability->water_supply;
                    $gas = $month_profitability->gas;
                    $cleaning = $month_profitability->cleaning;
                    $garbage_removal = $month_profitability->garbage_removal;
                    $other_expenses = $month_profitability->other_expenses;
                    $date_arr[] = substr($month_profitability->date,0,-3);
                    break;
                }
            }
        }
        /* Если у нас даты включают несколько месяцев*/
        else{
            foreach($profitability_months as $value) {
                if ( strtotime(substr($value->date,0,-3).'-01') >= strtotime($start_date_new) AND strtotime(substr($value->date,0,-3).'-01') <= strtotime($end_date_new) ) {
                    
                    $month_profitability = Month_profitability::find($value->id);                                       
                    $rental_price += $month_profitability->rental_price;
                    $electricity += $month_profitability->electricity;
                    $water_supply += $month_profitability->water_supply;
                    $gas += $month_profitability->gas;
                    $cleaning += $month_profitability->cleaning;
                    $garbage_removal += $month_profitability->garbage_removal;
                    $other_expenses += $month_profitability->other_expenses;
                    $date_arr[] = substr($month_profitability->date,0,-3);
                }
            }
        }
        
        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }       

    /* Отображение месячной рентабельности со свежими данными*/
    public function profitability_month(Request $request){
        /* Получаем всю нужную информацию по нарядам */        
        /* Получаем зональную доходную часть */
        $zonal_assignment_income = Zonal_assignments_income::all();
        /* Получаем зональную расходную часть */
        $zonal_assignment_expense = Zonal_assignments_expense::all();
        /* Получаем доходную часть */
        $assignment_income = Assignments_income::all();
        /* Получаем расходную часть */
        $assignment_expense = Assignments_expense::all();
        /* Получаем курс валют */
        $usd = DB::table('exchange_rates')->select('usd')->get();
        foreach ($usd as $value) {
            $usd = $value->usd;
        }
        $eur = DB::table('exchange_rates')->select('eur')->get();
        foreach ($eur as $value) {
            $eur = $value->eur;
        }
        /* Получаем ежемесячные расходы */
        $profitability_months = Month_profitability::all();
        /* Проверяем наличие данных */
        $item = true;
        foreach($profitability_months as $value) {
            /* если есть данные в этом месяце, то обновляем */
            if (substr($request->date,0,-3) === substr($value->date,0,-3)) {
                $item = false;
                $month_profitability = Month_profitability::find($value->id);
                if (isset($request->rental_price)) {
                    $month_profitability->rental_price = $request->rental_price;
                }
                if (isset($request->electricity)) {
                    $month_profitability->electricity = $request->electricity;
                }
                if (isset($request->water_supply)) {
                    $month_profitability->water_supply = $request->water_supply;
                }
                if (isset($request->gas)) {
                    $month_profitability->gas = $request->gas;
                }
                if (isset($request->cleaning)) {
                    $month_profitability->cleaning = $request->cleaning;
                }
                if (isset($request->garbage_removal)) {
                    $month_profitability->garbage_removal = $request->garbage_removal;
                }
                if (isset($request->other_expenses)) {
                    $month_profitability->other_expenses = $request->other_expenses;
                }
                $month_profitability->save();
                
                $rental_price = $month_profitability->rental_price;
                $electricity = $month_profitability->electricity;
                $water_supply = $month_profitability->water_supply;                
                $gas = $month_profitability->gas;
                $cleaning = $month_profitability->cleaning;
                $garbage_removal = $month_profitability->garbage_removal;
                $other_expenses = $month_profitability->other_expenses;
                $date = $month_profitability->date;
                break;
            }
        }
        /* если нет, то добавляем */
        if ($item) {
            $new_month_profitability = new Month_profitability();
            $new_month_profitability->rental_price = $request->rental_price;
            $new_month_profitability->electricity = $request->electricity;
            $new_month_profitability->water_supply = $request->water_supply;
            $new_month_profitability->gas = $request->gas;
            $new_month_profitability->cleaning = $request->cleaning;
            $new_month_profitability->garbage_removal = $request->garbage_removal;
            $new_month_profitability->other_expenses = $request->other_expenses;
            $new_month_profitability->date = $request->date;
            $new_month_profitability->save();

            $rental_price = $request->rental_price;
            $electricity = $request->electricity;
            $water_supply = $request->water_supply;
            $gas = $request->gas;
            $cleaning = $request->cleaning;
            $garbage_removal = $request->garbage_removal;
            $other_expenses = $request->other_expenses;
            $date = $request->date;
        }

        $start_date = $date;
        $end_date = $date;
        $date_arr[] = substr($date,0,-3);

        return view('assignments_admin.profitability_month_index',
        [
            'zonal_assignment_income' => $zonal_assignment_income,
            'zonal_assignment_expense' => $zonal_assignment_expense,
            'assignment_income' => $assignment_income,
            'assignment_expense' => $assignment_expense,
            'usd' => $usd,
            'eur' => $eur,
            'rental_price' => $rental_price,
            'electricity' => $electricity,
            'water_supply' => $water_supply,
            'gas' => $gas,
            'cleaning' => $cleaning,
            'garbage_removal' => $garbage_removal,
            'other_expenses' => $other_expenses,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'date_arr' => $date_arr,
        ]);
    }    

}
