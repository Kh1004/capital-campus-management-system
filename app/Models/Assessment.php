<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assessment_id';

    protected $fillable = [
        'student_id',
        'module_no',
        'file_upload',
        'submission_date',
        'marks',
        'status',
        'marking_status',
    ];
}
