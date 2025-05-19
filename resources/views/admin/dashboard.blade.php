@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Admin Dashboard</h5>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Course
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-primary" role="alert">
                        <i class="fas fa-user-shield me-2"></i>
                        Welcome back, <strong>{{ auth()->user()->name }}</strong>! You are logged in as an administrator.
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Courses</h6>
                                            <h2 class="mb-0">{{ $totalCourses ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('admin.courses.index') }}">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Enrollments</h6>
                                            <h2 class="mb-0">{{ $totalEnrollments ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('admin.enrollments.index') }}">Manage Enrollments</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Active Students</h6>
                                            <h2 class="mb-0">{{ $activeStudents ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('admin.enrollments.index') }}?status=enrolled">View Enrolled</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Completed Courses</h6>
                                            <h2 class="mb-0">{{ $completedEnrollments ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="{{ route('admin.enrollments.index') }}?status=completed">View Completed</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Courses -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Recent Courses</h6>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(isset($recentCourses) && $recentCourses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Course Name</th>
                                                <th>Type</th>
                                                <th>Duration</th>
                                                <th>Start Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentCourses as $course)
                                                <tr>
                                                    <td>{{ $course->course_name }}</td>
                                                    <td><span class="badge bg-primary">{{ $course->course_type }}</span></td>
                                                    <td>{{ $course->course_duration }}</td>
                                                    <td>{{ $course->start_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.courses.show', $course->course_id) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('admin.courses.edit', $course->course_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No courses found. Get started by adding a new course.</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Course
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Quick Actions</span>
                                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-graduate me-1"></i> All Enrollments
                                    </a>
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('admin.courses.create') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-plus-circle me-2"></i> Add New Course
                                    </a>
                                    <a href="{{ route('admin.enrollments.index') }}?status=enrolled" class="list-group-item list-group-item-action">
                                        <i class="fas fa-user-check me-2"></i> View Active Enrollments
                                    </a>
                                    <a href="{{ route('admin.enrollments.index') }}?status=completed" class="list-group-item list-group-item-action">
                                        <i class="fas fa-check-circle me-2"></i> View Completed Courses
                                    </a>
                                    <a href="{{ route('admin.enrollments.index') }}?status=dropped" class="list-group-item list-group-item-action">
                                        <i class="fas fa-user-times me-2"></i> View Dropped Students
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recent Activity</div>
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-plus-circle text-success"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">New Course Added</h6>
                                            <p class="text-muted mb-0 small">Web Development Bootcamp was added</p>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-plus text-info"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">New User Registered</h6>
                                            <p class="text-muted mb-0 small">John Doe registered as a student</p>
                                            <small class="text-muted">5 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-book text-warning"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">Course Updated</h6>
                                            <p class="text-muted mb-0 small">Data Science course details were updated</p>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Enrollments -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Recent Enrollments</h6>
                                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(isset($recentEnrollments) && $recentEnrollments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Course</th>
                                                <th>Type</th>
                                                <th>Enrolled On</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentEnrollments as $enrollment)
                                                <tr>
                                                    <td>
                                                        <div>{{ $enrollment->student_name }}</div>
                                                        <small class="text-muted">{{ $enrollment->student_email }}</small>
                                                    </td>
                                                    <td>{{ $enrollment->course_name }}</td>
                                                    <td>{{ $enrollment->course_type }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($enrollment->status === 'enrolled') bg-primary
                                                            @elseif($enrollment->status === 'completed') bg-success
                                                            @elseif($enrollment->status === 'dropped') bg-danger
                                                            @endif">
                                                            {{ ucfirst($enrollment->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    No recent enrollments found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
        margin-bottom: 1.5rem;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
    }
    .bg-primary {
        background-color: #4e73df !important;
    }
    .bg-success {
        background-color: #1cc88a !important;
    }
    .bg-warning {
        background-color: #f6c23e !important;
    }
    .text-primary {
        color: #4e73df !important;
    }
    .text-success {
        color: #1cc88a !important;
    }
    .text-warning {
        color: #f6c23e !important;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Any additional JavaScript can go here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
