<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Cars_in_service;

class Client extends Model
{
    /* Получить клиента по машине */
    public static function get_client_by_car_id($car_id){
        
        return Client::find(Cars_in_service::find($car_id)->owner_client_id);
    }
}
