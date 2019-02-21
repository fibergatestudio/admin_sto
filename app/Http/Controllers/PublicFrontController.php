<?php

/*
* Контроллер, который отвечает за все страницы, доступные посетителям без регистрации и специальных прав
* (в том числе и НЕ сотрдуникам автосервиса)
* В частности - форма записи на приём
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client_request;

class PublicFrontController extends Controller
{
    /*
    * Страницы с формой записи на приём
    */
    public function show_make_appointment_form(){
        return view('public_front.make_appointment');
    }

    /* Обработка данных из формы записи на приём */
    public function appointment_form_post_processing(Request $request){
        // Получаем данные из запроса
        $customer_name = $request->customerName;
        $car_model = $request->customerCarModel;
        $car_mark = $request->customerCarMark;
        $car_year_of_production = $request->customerCarYearOfProduction;

        // Создаём запись в базе данных
        $new_client_request = new Client_request();
        $new_client_request->client_name = $customer_name;
        $new_client_request->client_car_year_of_production = $car_year_of_production;
        $new_client_request->client_car_mark = $car_mark;
        $new_client_request->client_car_model = $car_model;
        $new_client_request->save();

        /* - добавление в логи создание записи на приём - */
        $create_appointment_log = new Appointment_log();
        $create_appointment_log_entry->client_id = $client_id;
        $create_appointment_log_entry->author_id = $author_id;
        $create_appointment_log_entry->car_id = $car_id;

        /* - Имя клиента - */
        $client = Clients::find($client_id);
        $client_name = $client->general_name;
        /* - Имя автора - */
        $author = Users::find($author_id); 
        $author_name = $author->general_name;
        /* - Название машины - */
        $car = Cars_in_service::find($car_id);
        $car_name = $car->general_name;

        $create_appointment_log_entry->text = 'Создание записи на приём машины -' .$car_name. 'клиента - ' .$client_name. 'автор записи - ' .$authoir_name. 'дата - ' .date('Y-m-d');  //текст лога о создании записи на приём машины(название) клиента(имя), автором(имя), дата(date)

        $create_appointment_log_entry->save();
         9db85e4a3437e2eec2c0f3ec4f89f53b4bfb58e5

        // !! TODO: сделать защиту от спама

        // Отображаем страницу "успех"
        return view('public_front.appointment_success');
    }
}
