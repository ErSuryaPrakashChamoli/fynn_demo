<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReportingHistory extends Model
{
    protected $table = 'employee_reporting_history';

    protected $fillable = [
        'employee_id',
        'old_superviser_id',
        'old_manager_id',
        'old_cluster_id',
        'new_superviser_id',
        'new_manager_id',
        'new_cluster_id',
        'effective_date',
        'change_type',
        'updated_by',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];
}