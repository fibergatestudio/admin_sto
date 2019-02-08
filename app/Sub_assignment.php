<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_assignment extends Model
{
    protected $table = "sub_assignments";

    protected $fillable = [
        'id', 'title','order', 'status',
    ];
}
