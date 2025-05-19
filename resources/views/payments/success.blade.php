@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="success-animation">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <h5 class="mb-4">Thank you for your payment</h5>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body text-start">
                                <h6 class="card-title">Order Details</h6>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Course:</span>
                                    <span class="fw-bold">{{ $course->course_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Amount Paid:</span>
                                    <span class="fw-bold">₹{{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transaction ID:</span>
                                    <span class="text-muted">{{ $payment->transaction_id }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Date:</span>
                                    <span class="text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <p class="text-muted mb-4">
                            You have been successfully enrolled in the course. You can now access all course materials from your dashboard.
                        </p>
                        
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="{{ route('student.my-courses') }}" class="btn btn-primary px-4">
                                <i class="fas fa-book-open me-2"></i>Go to My Courses
                            </a>
                            <a href="{{ route('student.courses.show', $course) }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-play-circle me-2"></i>Start Learning
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-animation {
    margin: 0 auto;
    width: 100px;
    height: 100px;
    margin-bottom: 2rem;
}

.checkmark {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: block;
    stroke-width: 5;
    stroke: #4bb71b;
    stroke-miterlimit: 10;
    box-shadow: inset 0 0 0 #4bb71b;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 5;
    stroke-miterlimit: 10;
    stroke: #4bb71b;
    fill: none;
    animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

@keyframes scale {
    0%, 100% { transform: none; }
    50% { transform: scale3d(1.1, 1.1, 1); }
}

@keyframes fill {
    100% { box-shadow: inset 0 0 0 100vh #f8f9fa; }
}
</style>
@endsection
