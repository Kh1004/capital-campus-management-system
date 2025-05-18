<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // GET all courses
    public function index()
    {
        $courses = Course::all();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data' => $courses
        ], 200);
    }

    // POST create new course
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name'         => 'required|string|max:255',
            'course_type'         => 'required|string|max:100',
            'description'         => 'nullable|string',
            'course_fee'          => 'required|numeric',
            'course_duration'     => 'required|int|max:11',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'email'               => 'required|email',
            'academic_calender_id'=> 'nullable|integer|exists:academic_calenders,id'
        ]);

        $course = Course::create($validated);

        return response()->json([
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    // GET single course
    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => $course
        ], 200);
    }

    // PUT update course
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validated = $request->validate([
            'course_name'         => 'sometimes|required|string|max:255',
            'course_type'         => 'sometimes|required|string|max:100',
            'description'         => 'sometimes|nullable|string',
            'course_fee'          => 'sometimes|required|numeric',
            'course_duration'     => 'sometimes|required|integer|max:50',
            'start_date'          => 'sometimes|required|date',
            'end_date'            => 'sometimes|required|date|after_or_equal:start_date',
            'email'               => 'sometimes|required|email',
            'academic_calender_id'=> 'sometimes|nullable|integer|exists:academic_calenders,id'
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'data' => $course
        ], 200);
    }

    // DELETE course
    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
