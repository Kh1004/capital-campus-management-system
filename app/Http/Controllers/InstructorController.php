<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        return response()->json([
            'message' => 'Instructors retrieved successfully',
            'data' => $instructors
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:instructors,email',
            'phone'          => 'nullable|string|max:20',
            'password'       => 'required|string|min:6',
            'specialization' => 'nullable|string|max:255',
            'profile_status' => 'required|string',
            'picture'        => 'nullable|string',
            'salary'         => 'required|numeric',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $instructor = Instructor::create($validated);

        return response()->json([
            'message' => 'Instructor created successfully',
            'data' => $instructor
        ], 201);
    }

    public function show($id)
    {
        $instructor = Instructor::find($id);
        if (!$instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

        return response()->json([
            'message' => 'Instructor retrieved successfully',
            'data' => $instructor
        ]);
    }

    public function update(Request $request, $id)
    {
        $instructor = Instructor::find($id);
        if (!$instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

        $validated = $request->validate([
            'name'           => 'sometimes|required|string|max:255',
            'email'          => 'sometimes|required|email|unique:instructors,email,' . $id . ',instructor_id',
            'phone'          => 'sometimes|nullable|string|max:20',
            'password'       => 'sometimes|nullable|string|min:6',
            'specialization' => 'sometimes|nullable|string|max:255',
            'profile_status' => 'sometimes|required|boolean',
            'picture'        => 'sometimes|nullable|string',
            'salary'         => 'sometimes|required|numeric',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $instructor->update($validated);

        return response()->json([
            'message' => 'Instructor updated successfully',
            'data' => $instructor
        ]);
    }

    public function destroy($id)
    {
        $instructor = Instructor::find($id);
        if (!$instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

        $instructor->delete();

        return response()->json([
            'message' => 'Instructor deleted successfully'
        ]);
    }
}
