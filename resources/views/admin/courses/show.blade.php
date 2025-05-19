@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Course Details</h5>
                    <div>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>{{ $course->course_name }}</h4>
                            <p class="text-muted">{{ $course->course_type }} Course</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge bg-primary">{{ $course->course_duration }}</span>
                            <h4 class="mt-2">₹{{ number_format($course->course_fee, 2) }}</h4>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $course->description ?? 'No description available.' }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Schedule</h5>
                            <ul class="list-unstyled">
                                <li><strong>Start Date:</strong> {{ $course->start_date->format('F j, Y') }}</li>
                                <li><strong>End Date:</strong> {{ $course->end_date->format('F j, Y') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Contact Information</h5>
                            <ul class="list-unstyled">
                                <li><strong>Email:</strong> {{ $course->email }}</li>
                                <li><strong>Course ID:</strong> {{ $course->course_id }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.courses.edit', $course->course_id) }}" class="btn btn-warning me-md-2">
                            Edit Course
                        </a>
                        <form action="{{ route('admin.courses.destroy', $course->course_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this course?')">
                                Delete Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
