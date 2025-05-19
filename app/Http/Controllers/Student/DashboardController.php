<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Show the student dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all available courses
        $courses = Course::latest()->get();
        
        // Get enrolled course IDs for the current user
        $enrolledCourseIds = $user->enrolledCourses->pluck('course_id')->toArray();
        
        return view('student.dashboard', [
            'courses' => $courses,
            'enrolledCourseIds' => $enrolledCourseIds
        ]);
    }
    
    /**
     * Show the enrolled courses for the student.
     *
     * @return \Illuminate\View\View
     */
    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()
            ->withPivot('status', 'enrolled_at')
            ->orderBy('course_user.enrolled_at', 'desc')
            ->paginate(10); // Add pagination with 10 items per page
        
        return view('student.my-courses', [
            'enrolledCourses' => $enrolledCourses
        ]);
    }
}
