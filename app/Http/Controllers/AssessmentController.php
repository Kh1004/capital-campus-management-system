<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Assessments retrieved successfully',
            'data' => Assessment::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'       => 'required|integer|exists:students,student_id',
            'module_no'        => 'required|string|max:50',
            'file_upload'      => 'nullable|string|max:255',
            'submission_date'  => 'nullable|date',
            'marks'            => 'nullable|numeric|min:0|max:100',
            'status'           => 'required|in:submitted,pending,late',
            'marking_status'   => 'required|in:marked,not_marked',
        ]);

        $assessment = Assessment::create($validated);

        return response()->json([
            'message' => 'Assessment created successfully',
            'data' => $assessment
        ], 201);
    }

    public function show($assessment_id)
    {
        $assessment = Assessment::find($assessment_id);
        if (!$assessment) {
            return response()->json(['message' => 'Assessment not found'], 404);
        }

        return response()->json([
            'message' => 'Assessment retrieved successfully',
            'data' => $assessment
        ], 200);
    }

    public function update(Request $request, $assessment_id)
    {
        $assessment = Assessment::find($assessment_id);
        if (!$assessment) {
            return response()->json(['message' => 'Assessment not found'], 404);
        }

        $validated = $request->validate([
            'student_id'       => 'sometimes|required|integer|exists:students,student_id',
            'module_no'        => 'sometimes|required|string|max:50',
            'file_upload'      => 'sometimes|nullable|string|max:255',
            'submission_date'  => 'sometimes|nullable|date',
            'marks'            => 'sometimes|nullable|numeric|min:0|max:100',
            'status'           => 'sometimes|required|in:submitted,pending,late',
            'marking_status'   => 'sometimes|required|in:marked,not_marked',
        ]);

        $assessment->update($validated);

        return response()->json([
            'message' => 'Assessment updated successfully',
            'data' => $assessment
        ], 200);
    }

    public function destroy($assessment_id)
    {
        $assessment = Assessment::find($assessment_id);
        if (!$assessment) {
            return response()->json(['message' => 'Assessment not found'], 404);
        }

        $assessment->delete();

        return response()->json([
            'message' => 'Assessment deleted successfully'
        ], 200);
    }
}
