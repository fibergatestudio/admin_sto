<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Cars_in_service;
use App\Client;

class Assignment extends Model
{
    /* Получить имя клиента */
    public function get_client_name(){
        /* Получаем данные по машине */
        $car = Cars_in_service::find($this->car_id);
        $client_id = $car->owner_client_id;

        /* Получаем из данных по машине данные по владельцу */
        $client = Client::find($client_id);

        /* Возвращаем имя клиента */
        return $client->general_name;
    }

    /* Получить название машины */
    public function get_car_name(){
        $car = Cars_in_service::find($this->car_id);
        return $car->general_name;
    }
    
}
