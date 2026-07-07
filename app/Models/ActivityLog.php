<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //

    protected $table = 'activity_log';

    protected $guarded = [];

    protected $casts = [
        'attribute_changes' => 'array',
        'properties' => 'array',
    ];

    public function causer(){
            return $this->morphTo();
        }

    public function subject(){
            return $this->morphTo();
        }
}
