@extends('layouts.app')

@push('styles')
<style>
    .enroll-confirm {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .enroll-confirm .modal-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .enroll-confirm .modal-footer {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .btn-confirm {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-confirm:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Student Dashboard</h1>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Available Courses</h2>
                <a href="{{ route('student.my-courses') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book me-2"></i>My Enrolled Courses
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @forelse($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->course_name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->course_name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $course->course_type }}</h6>
                        <p class="card-text">{{ Str::limit($course->description, 150) }}</p>
                        <ul class="list-unstyled">
                            <li><i class="far fa-calendar-alt me-2"></i> {{ $course->start_date->format('M d, Y') }} - {{ $course->end_date->format('M d, Y') }}</li>
                            <li><i class="far fa-clock me-2"></i> {{ $course->course_duration }}</li>
                            <li><i class="fas fa-rupee-sign me-2"></i> {{ number_format($course->course_fee, 2) }}</li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent">
                        @if(in_array($course->course_id, $enrolledCourseIds))
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Enrolled</span>
                                <a href="{{ route('student.courses.show', $course->course_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Course
                                </a>
                            </div>
                        @else
                            <form action="{{ route('student.course.enroll', $course->course_id) }}" method="POST" class="enroll-form">
                                @csrf
                                <button type="button" class="btn btn-primary w-100 enroll-btn" data-bs-toggle="modal" data-bs-target="#enrollModal{{ $course->course_id }}">
                                    <i class="fas fa-plus-circle me-2"></i>Enroll Now
                                </button>

                                <!-- Confirmation Modal -->
                                <div class="modal fade" id="enrollModal{{ $course->course_id }}" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content enroll-confirm">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="enrollModalLabel">Confirm Enrollment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to enroll in <strong>{{ $course->course_name }}</strong>?</p>
                                                <p class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Course Fee: ₹{{ number_format($course->course_fee, 2) }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm Enrollment</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No courses available at the moment.</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Confirmation Modal -->
<!-- Enrollment modal removed -->

@push('styles')
<style>
    .enroll-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
    }
    .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }
    .modal-footer {
        border-top: none;
        padding-top: 0;
    }
    #confirmEnrollBtn {
        min-width: 150px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Enrollment modal and confirmation code removed
    // Direct form submission is now used
</script>
@endpush

@endsection
