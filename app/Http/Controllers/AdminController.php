<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Admins retrieved successfully',
            'data' => Admin::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'picture' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $admin = Admin::create($validated);

        return response()->json([
            'message' => 'Admin created successfully',
            'data' => $admin
        ], 201);
    }

    public function show($admin_id)
    {
        $admin = Admin::find($admin_id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        return response()->json([
            'message' => 'Admin retrieved successfully',
            'data' => $admin
        ], 200);
    }

    public function update(Request $request, $admin_id)
    {
        $admin = Admin::find($admin_id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:admins,email,' . $admin_id . ',admin_id',
            'password' => 'sometimes|required|string|min:6',
            'picture' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'message' => 'Admin updated successfully',
            'data' => $admin
        ], 200);
    }

    public function destroy($admin_id)
    {
        $admin = Admin::find($admin_id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ], 200);
    }
}
