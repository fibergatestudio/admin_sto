<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Cars_in_service;
use App\Client;

class Assignment extends Model
{
    protected $table = "assignments";

    protected $fillable = [
        'id', 'title','order', 'status',
    ];
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

    /* Получить id клиента */
    public function get_client_id(){
        /* Получаем данные по машине */
        $car = Cars_in_service::find($this->car_id);
        $client_id = $car->owner_client_id;

        /* Возвращаем id клиента */
        return $client_id;
    }

    /* Получить название машины */
    public function get_car_name(){
        $car = Cars_in_service::find($this->car_id);
        return $car->general_name;
    }

    /* Получить brand машины */
    public function get_car_brand(){
        $car = Cars_in_service::find($this->car_id);
        return $car->car_brand;
    }

    /* Получить model машины */
    public function get_car_model(){
        $car = Cars_in_service::find($this->car_id);
        return $car->car_model;
    }

    /* Получить год машины */
    public function get_car_year(){
        $car = Cars_in_service::find($this->car_id);
        return $car->release_year;
    }

    /* Получить mileage машины */
    public function get_car_mileage_km(){
        $car = Cars_in_service::find($this->car_id);
        return $car->mileage_km;
    }

    /* Получить mileage машины */
    public function get_car_fuel_type(){
        $car = Cars_in_service::find($this->car_id);
        return $car->fuel_type;
    }

    /* Получить гос. номер машины */
    public function get_car_reg_number(){
        $car = Cars_in_service::find($this->car_id);
        return $car->reg_number;
    }

    /* Получить engine_capacity машины */
    public function get_car_engine_capacity(){
        $car = Cars_in_service::find($this->car_id);
        return $car->engine_capacity;
    }

    /* Получить vin машины */
    public function get_car_vin_number(){
        $car = Cars_in_service::find($this->car_id);
        return $car->vin_number;
    }

    /* Получить color машины */
    public function get_car_color(){
        $car = Cars_in_service::find($this->car_id);
        return $car->car_color;
    }
    
    
}
