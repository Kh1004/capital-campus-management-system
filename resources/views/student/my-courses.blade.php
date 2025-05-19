@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Enrolled Courses</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($enrolledCourses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Type</th>
                                        <th>Enrolled On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrolledCourses as $course)
                                        <tr>
                                            <td>
                                                <a href="{{ route('student.courses.show', $course->course_id) }}">
                                                    {{ $course->course_name }}
                                                </a>
                                            </td>
                                            <td>{{ $course->course_type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($course->pivot->enrolled_at)->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($course->pivot->status === 'enrolled') bg-primary
                                                    @elseif($course->pivot->status === 'completed') bg-success
                                                    @elseif($course->pivot->status === 'dropped') bg-danger
                                                    @endif">
                                                    {{ ucfirst($course->pivot->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('student.courses.show', $course->course_id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i> View Course
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $enrolledCourses->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            You haven't enrolled in any courses yet. <a href="{{ route('welcome') }}">Browse courses</a> to get started.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
