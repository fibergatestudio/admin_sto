<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Client;

class Cars_in_service extends Model
{
    //

    protected $table = 'cars_in_service';

    /* Получить имя клиента : функция */
    public function get_client(){
        $client_id = $this->owner_client_id;
        return Client::find($client_id);
    }


    public function car_model()
    {
        return $this->hasOne('App\Car_model_list','general_name','general_name');
    }

}
