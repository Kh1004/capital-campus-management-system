<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Staff members retrieved successfully',
            'data' => Staff::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:staff,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|max:50',
            'salary'   => 'required|numeric|min:0',
            'picture'  => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $staff = Staff::create($validated);

        return response()->json([
            'message' => 'Staff created successfully',
            'data' => $staff
        ], 201);
    }

    public function show($staff_id)
    {
        $staff = Staff::find($staff_id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        return response()->json([
            'message' => 'Staff retrieved successfully',
            'data' => $staff
        ], 200);
    }

    public function update(Request $request, $staff_id)
    {
        $staff = Staff::find($staff_id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:staff,email,' . $staff_id . ',staff_id',
            'phone'    => 'nullable|string|max:20',
            'password' => 'sometimes|required|string|min:6',
            'role'     => 'sometimes|required|string|max:50',
            'salary'   => 'sometimes|required|numeric|min:0',
            'picture'  => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $staff->update($validated);

        return response()->json([
            'message' => 'Staff updated successfully',
            'data' => $staff
        ], 200);
    }

    public function destroy($staff_id)
    {
        $staff = Staff::find($staff_id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $staff->delete();

        return response()->json([
            'message' => 'Staff deleted successfully'
        ], 200);
    }
}
