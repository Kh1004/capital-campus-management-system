<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $primaryKey = 'instructor_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'specialization',
        'profile_status',
        'picture',
        'salary',
    ];

    protected $hidden = [
        'password',
    ];
}
