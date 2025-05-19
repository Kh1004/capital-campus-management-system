<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and recent activities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'totalCourses' => Course::count(),
            'activeStudents' => User::where('role', 'student')->count(),
            'upcomingCourses' => Course::where('start_date', '>', now())->count(),
            'recentCourses' => Course::latest()->take(5)->get(),
            'totalEnrollments' => DB::table('course_user')->count(),
            'completedEnrollments' => DB::table('course_user')->where('status', 'completed')->count(),
            'activeEnrollments' => DB::table('course_user as cu')
                ->join('courses as c', 'cu.course_id', '=', 'c.course_id')
                ->where('cu.status', 'enrolled')
                ->where('c.end_date', '>=', now())
                ->count(),
            'recentEnrollments' => DB::table('course_user')
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
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
