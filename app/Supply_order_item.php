<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supply_order;

class Supply_order_item extends Model
{
    public function order(){
        return $this->belongsTo('Supply_order');
    }
}
