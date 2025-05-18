<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Get student details by student ID
     *
     * @param  int  $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStudentDetails($studentId)
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Student details retrieved successfully',
            'data' => $student,
        ], 200);
    }

    /**
     * Get courses the student is enrolled in
     *
     * @param  int  $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStudentCourses($studentId)
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        // Get courses enrolled by the student (Assuming a relationship between Student and Course)
        $courses = $student->courses;

        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
        ], 200);
    }
}
