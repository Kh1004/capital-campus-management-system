@extends('layouts.app')

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.my-courses') }}">My Courses</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $course->course_name }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>{{ $course->course_name }}</h1>
                    <p class="lead text-muted">{{ $course->course_type }}</p>
                </div>
                @if(!isset($enrollment))
                    <button type="button" class="btn btn-primary" onclick="confirmEnrollment('{{ $course->course_name }}', {{ $course->price ?? 999 }})">
                        <i class="fas fa-shopping-cart me-2"></i>Enroll Now (₹{{ number_format($course->price ?? 999, 2) }})
                    </button>

                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="confirmEnrollModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-graduate me-2"></i>Confirm Enrollment
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-info-circle text-primary fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 id="confirmCourseName" class="mb-1"></h5>
                                            <p class="mb-0">Fee: <span id="confirmCourseFee" class="fw-bold"></span></p>
                                        </div>
                                    </div>
                                    <p>Are you sure you want to enroll in this course? You will be redirected to the payment page.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="proceedToPayment()">
                                        <i class="fas fa-check-circle me-1"></i> Confirm & Proceed to Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div>
                        @if($enrollment->status !== 'completed')
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#unenrollModal">
                                <i class="fas fa-sign-out-alt me-2"></i>Unenroll
                            </button>

                            <!-- Unenroll Confirmation Modal -->
                            <div class="modal fade" id="unenrollModal" tabindex="-1" aria-labelledby="unenrollModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="unenrollModalLabel">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Confirm Unenrollment
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="fas fa-exclamation-circle text-danger fa-3x"></i>
                                                </div>
                                                <div>
                                                    <h5>Are you sure you want to unenroll?</h5>
                                                    <p class="mb-0">You will lose access to all course materials and progress. This action cannot be undone.</p>
                                                </div>
                                            </div>
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                You may need to re-enroll and pay again to regain access.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <form action="{{ route('student.course.unenroll', $course->course_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Yes, Unenroll Me
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            
            @if(isset($enrollment))
                <!-- Course Progress -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Course Progress</h6>
                            <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : 'primary' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            @php
                                $progress = $enrollment->status === 'completed' ? 100 : 0;
                                // You can calculate actual progress based on completed lessons/modules
                            @endphp
                            <div class="progress-bar bg-{{ $enrollment->status === 'completed' ? 'success' : 'primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ $progress }}%" 
                                 aria-valuenow="{{ $progress }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small>Enrolled on: {{ $enrollment->enrolled_at->format('M d, Y') }}</small>
                            @if($enrollment->completed_at)
                                <small>Completed on: {{ $enrollment->completed_at->format('M d, Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Enroll Now Button -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Ready to start learning?</h5>
                        <p class="text-muted mb-4">Enroll now to get full access to this course.</p>
                        <button type="button" class="btn btn-primary btn-lg w-100" onclick="alert('You clicked Enroll Now for {{ addslashes($course->course_name) }}\nCourse Fee: ₹{{ number_format($course->course_fee, 2) }}'); confirmEnrollment('{{ addslashes($course->course_name) }}', {{ $course->course_fee }});">
                            <i class="fas fa-shopping-cart me-2"></i>Enroll Now for ₹{{ number_format($course->course_fee, 2) }}
                        </button>
                        <p class="small text-muted mt-2 mb-0">30-day money-back guarantee</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Course Content -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Course Content</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($modules as $module)
                            <div class="list-group-item
                                {{ $loop->first ? 'border-top-0' : '' }}
                                {{ $loop->last ? 'border-bottom-0' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-folder-open text-warning me-2"></i>
                                        {{ $module->title }}
                                    </h6>
                                    <span class="badge bg-secondary">{{ $module->lessons->count() }} lessons</span>
                                </div>
                                
                                @if($module->lessons->count() > 0)
                                    <div class="mt-2 ps-4">
                                        @foreach($module->lessons as $lesson)
                                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <div>
                                                    <i class="fas fa-play-circle text-primary me-2"></i>
                                                    <a href="#" class="text-decoration-none">{{ $lesson->title }}</a>
                                                </div>
                                                <small class="text-muted">{{ $lesson->duration ?? '5 min' }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No modules available for this course yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Course Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">About This Course</h5>
                </div>
                <div class="card-body">
                    {!! $course->description !!}
                </div>
            </div>
        </div>
        
        <!-- Course Sidebar -->
        <div class="col-lg-4">
            <!-- Course Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Course Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="far fa-calendar-alt me-2 text-muted"></i> Start Date</span>
                            <span>{{ $course->start_date->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="far fa-calendar-check me-2 text-muted"></i> End Date</span>
                            <span>{{ $course->end_date->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="far fa-clock me-2 text-muted"></i> Duration</span>
                            <span>{{ $course->course_duration }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-tag me-2 text-muted"></i> Category</span>
                            <span>{{ $course->category ?? 'General' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-rupee-sign me-2 text-muted"></i> Fee</span>
                            <span>₹{{ number_format($course->course_fee, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Instructor Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Instructor</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ asset('images/default-avatar.png') }}" 
                             alt="Instructor" 
                             class="rounded-circle" 
                             width="100" 
                             height="100">
                    </div>
                    <h5>{{ $course->instructor ?? 'Capital Campus' }}</h5>
                    <p class="text-muted">Lead Instructor</p>
                    <p class="small">Experienced educator with years of teaching experience in this field.</p>
                </div>
            </div>
            
            <!-- Course Actions -->
            @if(isset($enrollment) && $enrollment->status !== 'completed')
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" id="markAsComplete">
                        <i class="fas fa-check-circle me-2"></i>Mark as Complete
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmEnrollModal" tabindex="-1" aria-labelledby="confirmEnrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmEnrollModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Confirm Enrollment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to enroll in the following course:</p>
                <div class="alert alert-info">
                    <h6 class="fw-bold" id="confirmCourseName"></h6>
                    <p class="mb-0">Course Fee: <span id="confirmCourseFee" class="fw-bold"></span></p>
                </div>
                <p>Are you sure you want to proceed with the enrollment?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="proceedToPayment()">
                    <i class="fas fa-check me-2"></i>Yes, Enroll Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="enrollModalLabel">
                    <i class="fas fa-lock me-2"></i>Secure Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" action="{{ route('payment.process', $course->course_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Course Fee:</span>
                            <span>₹{{ number_format($course->price ?? 999, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Amount:</span>
                            <span>₹{{ number_format($course->price ?? 999, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Payment Details</h5>
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                <span class="input-group-text"><i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard ms-2"></i></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="card_holder" class="form-label">Name on Card</label>
                            <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="John Doe" required>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lock me-2"></i>
                        Your payment is secured with 256-bit SSL encryption
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="payNowBtn">
                        <i class="fas fa-credit-card me-2"></i>Pay ₹{{ number_format($course->price ?? 999, 2) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Processing Modal -->
<div class="modal fade" id="processingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Processing...</span>
                </div>
                <h5 class="mb-3">Processing Payment</h5>
                <p class="text-muted">Please wait while we process your payment. Do not close this window.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmEnrollment(courseName, courseFee) {
        // Show an alert first
        if (!confirm(`Are you sure you want to enroll in "${courseName}" for ₹${courseFee.toLocaleString('en-IN')}?`)) {
            return; // User clicked 'Cancel' in the alert
        }
        
        // Set course details in the modal
        document.getElementById('confirmCourseName').textContent = courseName;
        document.getElementById('confirmCourseFee').textContent = '₹' + courseFee.toLocaleString('en-IN');
        
        // Show the confirmation modal
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmEnrollModal'));
        confirmModal.show();
    }
    
    function proceedToPayment() {
        // Hide the confirmation modal
        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmEnrollModal'));
        confirmModal.hide();
        
        // Show the payment modal after a short delay for smooth transition
        setTimeout(() => {
            const paymentModal = new bootstrap.Modal(document.getElementById('enrollModal'));
            paymentModal.show();
        }, 300);
    }
    // Auto-format card number
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
        });
    }

    // Auto-format expiry date
    const expiryDateInput = document.getElementById('expiry_date');
    if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Handle form submission
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show processing modal
            const processingModal = new bootstrap.Modal(document.getElementById('processingModal'));
            processingModal.show();
            
            // Simulate payment processing
            setTimeout(function() {
                // Submit the form
                paymentForm.submit();
            }, 2000);
        });
    }
</script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark course as complete
        const markAsCompleteBtn = document.getElementById('markAsComplete');
        if (markAsCompleteBtn) {
            markAsCompleteBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark this course as complete?')) {
                    fetch('{{ route("student.courses.complete", $course->course_id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
