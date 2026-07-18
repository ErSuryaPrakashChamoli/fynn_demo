<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStageHistory extends Model
{
    //

    // protected $fillable = [
    //     'customer_id',
    //     'stage_name',
    //     'status_value',
    //     'user_id',
    // ];

    protected $fillable = [
        'customer_id',
        'stage_id',
        'remarks',
        'stage_name',
        'status_value',
        'user_id',
        'created_by',
        'updated_by',
    ];

    // protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
