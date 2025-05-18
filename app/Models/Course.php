<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id';
    protected $fillable = [
        'course_name',
        'course_type',
        'description',
        'course_fee',
        'course_duration',
        'start_date',
        'end_date',
        'email',
        'academic_calender_id',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
    }
}
