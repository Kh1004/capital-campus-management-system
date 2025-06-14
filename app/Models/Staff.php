<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'salary',
        'picture',
    ];

    protected $hidden = [
        'password',
    ];
}
