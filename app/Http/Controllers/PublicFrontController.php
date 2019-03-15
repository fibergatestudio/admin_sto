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

        // !! TODO: сделать защиту от спама

        // Отображаем страницу "успех"
        return view('public_front.appointment_success');
    }
}
