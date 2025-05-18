<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Staff;
use App\Models\Student;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::apiResource('students', StudentController::class);
Route::apiResource('instructors', InstructorController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('staffs', StaffController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('notes', NoteController::class);
Route::apiResource('assessments', AssessmentController::class);
Route::apiResource('modules', ModuleController::class);
Route::apiResource('payments', PaymentController::class);
Route::post('/login', [AuthController::class, 'login']);

