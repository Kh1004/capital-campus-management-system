<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all(); 
        return response()->json([
            'message' => 'Students retrieved successfully',
            'data' => $students
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reg_no'          => 'required|string|max:50|unique:students,reg_no',
            'course_id'       => 'required|integer|exists:courses,course_id',
            'full_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:students,email',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'date_of_birth'   => 'nullable|date',
            'password'        => 'required|string|min:6',
            'picture'         => 'nullable|string',
            'enrollment_date' => 'nullable|date',
            'verify_token'    => 'nullable|string|max:100',
            'payment_status'  => 'required|in:paid,unpaid',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $student = Student::create($validated);

        return response()->json([
            'message' => 'Student created successfully',
            'data' => $student
        ], 201);
    }

    public function show($student_id)
    {
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'Student retrieved successfully',
            'data' => $student
        ], 200);
    }

    public function edit(Student $student)
    {
        return response()->json([
            'message' => 'Student retrieved successfully',
            'data' => $student
        ], 200);
    }

    public function update(Request $request, $student_id)
    {
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        $validated = $request->validate([
            'reg_no' => 'sometimes|required|string|max:50',
            'phone'      => 'sometimes|nullable|string|max:20',
            'address'    => 'sometimes|nullable|string|max:255',
            'status'     => 'sometimes|required|boolean',
        ]);

        $student->update($validated);
        return response()->json([
            'message' => 'Student updated successfully',
            'data' => $student
        ], 200);
    }

    public function destroy($student_id)
    {
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }
        $student->delete();
        return response()->json([
            'message' => 'Student deleted successfully',
        ], 200);
    }
}
