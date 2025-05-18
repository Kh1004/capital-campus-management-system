<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;   // ← change base class
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;    // ← add traits

    protected $table      = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'reg_no',
        'course_id',
        'full_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'password',
        'picture',
        'enrollment_date',
        'verify_token',
        'payment_status',
    ];

    protected $hidden = ['password'];  
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }
}
