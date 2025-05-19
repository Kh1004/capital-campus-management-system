<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class CourseUser extends Pivot
{
    protected $table = 'course_user';
    
    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    public $incrementing = true;
}
