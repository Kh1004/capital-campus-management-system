<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $courses = \App\Models\Course::latest()->take(4)->get();
    return view('welcome', compact('courses'));
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Payment and Enrollment Routes
Route::middleware('auth')->group(function () {
    // Course enrollment
    Route::post('/courses/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'enroll'])
        ->name('student.course.enroll')
        ->middleware('can:enroll,course');
        
    // Payment routes
    Route::get('/courses/{course}/payment', [\App\Http\Controllers\PaymentController::class, 'showPaymentForm'])
        ->name('payment.form');
    Route::post('/courses/{course}/process-payment', [\App\Http\Controllers\PaymentController::class, 'processPayment'])
        ->name('payment.process');
    Route::get('/payment/success/{payment}', [\App\Http\Controllers\PaymentController::class, 'paymentSuccess'])
        ->name('payment.success');
    Route::get('/payment/failure', [\App\Http\Controllers\PaymentController::class, 'paymentFailure'])
        ->name('payment.failure');
        
    // Student course management
    Route::get('/my-courses', [\App\Http\Controllers\EnrollmentController::class, 'myCourses'])
        ->name('student.my-courses');
    Route::get('/courses/{course}', [\App\Http\Controllers\Student\CourseController::class, 'show'])
        ->name('student.courses.show');
    Route::delete('/courses/{course}/unenroll', [\App\Http\Controllers\EnrollmentController::class, 'unenroll'])
        ->name('student.course.unenroll')
        ->middleware('can:unenroll,course');
});

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = Password::sendResetLink(
        $request->only('email')
    );
    
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Welcome Route (accessible to all)
Route::get('/welcome', function () {
    $courses = \App\Models\Course::latest()->take(4)->get();
    return view('welcome', compact('courses'));
})->name('welcome');

// Redirect root to welcome page
Route::get('/', function () {
    return redirect()->route('welcome');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Courses Resource
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Student Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])
        ->name('dashboard');
        
    // My Courses
    Route::get('/my-courses', [\App\Http\Controllers\Student\DashboardController::class, 'myCourses'])
        ->name('my-courses');
});

// Student Course Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Student Course Details
    Route::get('/courses/{course:course_id}', [\App\Http\Controllers\Student\CourseController::class, 'show'])
        ->name('courses.show');
        
    // Mark course as complete
    Route::post('/courses/{course}/complete', [\App\Http\Controllers\Student\CourseController::class, 'markAsComplete'])
        ->name('courses.complete');
        
    // Unenroll from course
    Route::delete('/courses/{course}/unenroll', [\App\Http\Controllers\EnrollmentController::class, 'unenroll'])
        ->name('courses.unenroll')
        ->middleware('can:unenroll,course');
});

// Profile Routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Change Password
    Route::get('/password/change', [\App\Http\Controllers\ProfileController::class, 'changePassword'])
        ->name('password.change');
    Route::put('/password/update', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('password.update');
});

// Course Enrollment Routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    // Course enrollment routes
    Route::post('/courses/{course}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'enroll'])
        ->name('student.course.enroll')
        ->middleware('can:enroll,course');
        
    Route::delete('/student/courses/{course}/unenroll', [\App\Http\Controllers\EnrollmentController::class, 'unenroll'])
        ->name('student.courses.unenroll')
        ->middleware('can:unenroll,course');
        
    Route::post('/courses/{course}/process-payment', [\App\Http\Controllers\PaymentController::class, 'processPayment'])
        ->name('payment.process')
        ->middleware('can:enroll,course');
        
    Route::get('/payment/success/{payment}', [\App\Http\Controllers\PaymentController::class, 'paymentSuccess'])
        ->name('payment.success')
        ->middleware('auth');
        
    Route::get('/payment/failure', [\App\Http\Controllers\PaymentController::class, 'paymentFailure'])
        ->name('payment.failure')
        ->middleware('auth');
});

// Admin Enrollment Management
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::put('/enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');
});

// All React routes
Route::get('/{any}', function () {
    return view('app'); // this blade loads React app
})->where('any', '.*');
