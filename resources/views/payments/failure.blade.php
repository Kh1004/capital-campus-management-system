@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="failure-animation mb-4">
                            <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="crossmark__check" fill="none" d="M16 16 36 36 M36 16 16 36"/>
                            </svg>
                        </div>
                        <h2 class="text-danger mb-3">Payment Failed</h2>
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @else
                            <p class="lead">We're sorry, but there was an issue processing your payment.</p>
                        @endif
                        
                        <div class="alert alert-warning text-start">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i> What could have happened?</h5>
                            <ul class="mb-0 mt-2">
                                <li>Insufficient funds in your account</li>
                                <li>Incorrect card details entered</li>
                                <li>Card expired or not supported</li>
                                <li>Network issues</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                            @if(isset($course))
                                <a href="{{ route('student.course.enroll', $course) }}" class="btn btn-primary px-4">
                                    <i class="fas fa-credit-card me-2"></i>Try Again
                                </a>
                            @else
                                <a href="{{ URL::previous() }}" class="btn btn-primary px-4">
                                    <i class="fas fa-credit-card me-2"></i>Try Again
                                </a>
                            @endif
                            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted small mb-1">Need help with your payment?</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-phone-alt me-1"></i> Contact Support
                                </a>
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-1"></i> FAQ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.failure-animation {
    margin: 0 auto;
    width: 100px;
    height: 100px;
}

.crossmark {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: block;
    stroke: #dc3545;
    stroke-width: 5;
    stroke-miterlimit: 10;
    box-shadow: inset 0 0 0 #f8d7da;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.crossmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 5;
    stroke-miterlimit: 10;
    stroke: #dc3545;
    fill: none;
    animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.crossmark__check {
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
