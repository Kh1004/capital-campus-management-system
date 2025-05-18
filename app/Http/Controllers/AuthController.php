<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Staff;
use App\Models\Student;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Admin
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            return response()->json(['success' => true, 'role' => 'admin', 'user' => $admin]);
        }

        // Instructor
        $instructor = Instructor::where('email', $email)->first();
        if ($instructor && Hash::check($password, $instructor->password)) {
            return response()->json(['success' => true, 'role' => 'instructor', 'user' => $instructor]);
        }

        // Staff
        $staff = Staff::where('email', $email)->first();
        if ($staff && Hash::check($password, $staff->password)) {
            return response()->json(['success' => true, 'role' => 'staff', 'user' => $staff]);
        }

        // Student
        $student = Student::where('email', $email)->first();
        if ($student && Hash::check($password, $student->password)) {
            return response()->json(['success' => true, 'role' => 'student', 'user' => $student]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }
}
