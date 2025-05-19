<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['price'];
    
    /**
     * Get the price attribute (alias for course_fee).
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        return $this->course_fee;
    }

    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'course_id';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'course_fee' => 'decimal:2',
    ];

    /**
     * The users that are enrolled in this course.
     */
    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->using(\App\Models\CourseUser::class)
            ->withPivot('status', 'enrolled_at', 'completed_at', 'notes', 'payment_id')
            ->withTimestamps();
    }
    
    /**
     * Get all payments for the course.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Check if a user is enrolled in this course.
     *
     * @param  int  $userId
     * @return bool
     */
    public function isEnrolled($userId)
    {
        return $this->enrolledStudents()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get all modules for the course.
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }
}
