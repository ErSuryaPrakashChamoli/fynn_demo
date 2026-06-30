<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //

     protected $fillable = [
        'country',
        'state',
        'city',
        'state_code',
        'city_code',
        'is_active',
    ];
}
