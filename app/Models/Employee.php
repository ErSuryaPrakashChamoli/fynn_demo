<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    //
     protected $fillable = [
        'emp_id',
        'emp_name',
        'email',
        'designation',
        'doj',
        'reporting_date',
        'superviser_id',
        'manager_id',
        'cluster_id',
        'cost_center',
        'unit_name',
        'category',
        'position',
    ];

    public function superviser()
    {
        return $this->belongsTo(Employee::class, 'superviser_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function clusterManager()
    {
        // 'cluster_id' आपके टेबल का फॉरेन की कॉलम है, जो किसी दूसरे Employee की 'id' को पॉइंट करेगा
        return $this->belongsTo(Employee::class, 'cluster_id');
    }

    public function getTargetAmountAttribute(): int
    {
        $categoryTargets = [
            'platinum' => 3500000,
            'gold'     => 3000000,
            'silver'   => 2500000,
        ];

        $category = strtolower($this->category ?? 'silver');

        return $categoryTargets[$category] ?? 2500000;
    }
    
}
