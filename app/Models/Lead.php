<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead extends Model
{
    //

    protected $fillable = [
        'employee_id',
        'customer_name',
        'mobile_no',
        'pan_number',
        'current_location',
        'job_location',
        'salary',
        'follow_up_date',
        'follow_up_type',
        'status',
        'next_follow_up_date',
        'remarks',
        'is_converted',
        'converted_customer_id',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'next_follow_up_date' => 'date',
        'is_converted' => 'boolean',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function convertedCustomer()
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    protected static function booted()
        {
            static::creating(function ($lead) {
                if (Auth::check() && blank($lead->employee_id)) {
                    $lead->employee_id = Auth::user()->employee?->id;
                }
            });
        }
}
