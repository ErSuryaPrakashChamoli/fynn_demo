<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'customer_id',
        'employee_id',
        'follow_up_date',
        'follow_up_type',
        'remarks',
        'next_follow_up_date',
        'status',
    ];

      protected $casts = [
        'follow_up_date' => 'date',
        'next_follow_up_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
