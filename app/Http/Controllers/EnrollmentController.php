<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Enroll the authenticated user in a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check if already enrolled - specify table name to avoid ambiguity
        if ($user->enrolledCourses()->where('courses.course_id', $course->course_id)->exists()) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }

        // Authorize the action
        $this->authorize('enroll', $course);

        // Enroll the user - use course_id as the foreign key
        $user->enrolledCourses()->attach($course->course_id, [
            'enrolled_at' => now(),
            'status' => 'enrolled'
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Unenroll the authenticated user from a course.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    /**
     * Unenroll the authenticated user from a course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function unenroll(Course $course)
    {
        $user = Auth::user();
        
        // Check if enrolled - specify table name to avoid ambiguity
        if (!$user->enrolledCourses()->where('courses.course_id', $course->course_id)->exists()) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // Authorize the action
        $this->authorize('unenroll', $course);

        try {
            // Unenroll the user - use course_id as the foreign key
            $user->enrolledCourses()->detach($course->course_id);
            
            return redirect()->route('student.dashboard')
                ->with('success', 'Successfully unenrolled from the course.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to unenroll from the course. Please try again.');
        }
    }

    /**
     * Display a listing of the authenticated user's enrolled courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()
            ->withPivot('status', 'enrolled_at')
            ->orderBy('course_user.enrolled_at', 'desc')
            ->paginate(10);

        return view('student.my-courses', compact('enrolledCourses'));
    }

    /**
     * Display all enrollments (admin only).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        
        $enrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.course_id')
            ->select(
                'course_user.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.course_name',
                'courses.course_type'
            )
            ->orderBy('course_user.enrolled_at', 'desc')
            ->paginate(15);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    /**
     * Update the status of an enrollment (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $enrollmentId
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $enrollmentId)
    {
        $this->authorize('update', Course::class);
        
        $request->validate([
            'status' => 'required|in:enrolled,completed,dropped',
            'notes' => 'nullable|string|max:1000'
        ]);

        $enrollment = DB::table('course_user')->where('id', $enrollmentId);
        
        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes
        ];

        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $enrollment->update($updateData);

        return redirect()->back()->with('success', 'Enrollment status updated successfully.');
    }
}
