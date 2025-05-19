<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        $enrollment = null;
        $modules = collect();
        
        // Only check enrollment for students
        if ($user->isStudent()) {
            // Check if user is enrolled in this course
            $isEnrolled = $user->enrolledCourses()
                ->where('courses.course_id', $course->course_id)
                ->exists();
            
            if ($isEnrolled) {
                // Get the enrollment details if user is enrolled
                $enrollment = $user->enrolledCourses()
                    ->where('courses.course_id', $course->course_id)
                    ->withPivot('status', 'enrolled_at', 'completed_at')
                    ->first()
                    ->pivot;
            }
        }
        
        // Get course modules/lessons for all users
        $modules = $course->modules()->with('lessons')->get();
        
        return view('student.course.show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'modules' => $modules
        ]);
    }
    
    /**
     * Show the payment form for enrolling in a course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function showEnrollForm($course)
    {
        $course = Course::where('course_id', $course)->firstOrFail();
        $user = Auth::user();
        
        // Check if user is already enrolled in this course
        if ($user->enrolledCourses()->where('courses.course_id', $course->course_id)->exists()) {
            return redirect()->route('student.courses.show', $course->course_id)
                ->with('info', 'You are already enrolled in this course.');
        }
        
        // Redirect to the payment form
        return redirect()->route('payment.form', $course->course_id);
    }
    
    /**
     * Mark a course as complete for the authenticated user.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsComplete(Course $course)
    {
        $user = Auth::user();
        
        // Check if user is enrolled in this course
        $enrollment = $user->enrolledCourses()
            ->where('courses.course_id', $course->course_id)
            ->first();
            
        if (!$enrollment) {
            return redirect()->back()
                ->with('error', 'You are not enrolled in this course.');
        }
        
        // Update the enrollment status to completed
        $user->enrolledCourses()->updateExistingPivot($course->course_id, [
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Course marked as completed successfully!');
    }
}
