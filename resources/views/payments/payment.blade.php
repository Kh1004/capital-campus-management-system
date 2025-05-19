@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Complete Your Enrollment</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Course: {{ $course->course_name }}</h5>
                        <p class="mb-0">Amount to pay: <strong>₹{{ number_format($amount, 2) }}</strong></p>
                    </div>

                    <form action="{{ route('payment.process', $course) }}" method="POST" id="payment-form">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        
                        <div class="mb-3">
                            <label for="card-holder" class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" id="card-holder" name="card_holder" 
                                   placeholder="Name on card" required>
                        </div>

                        <div class="mb-3">
                            <label for="card-number" class="form-label">Card Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="card-number" name="card_number" 
                                       placeholder="1234 5678 9012 3456" required>
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry-date" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiry-date" name="expiry_date" 
                                       placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cvv" name="cvv" 
                                           placeholder="123" required>
                                    <span class="input-group-text" data-bs-toggle="tooltip" 
                                          title="3-digit code on the back of your card">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock me-2"></i> Pay ₹{{ number_format($amount, 2) }}
                            </button>
                            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Course
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted small">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-lock me-1"></i> Secure Payment</span>
                        <span><i class="fas fa-shield-alt me-1"></i> 256-bit SSL Encryption</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format card number
    document.getElementById('card-number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formatted = value.replace(/(\d{4})(?=\d)/g, "$1 ");
        e.target.value = formatted.trim();
    });

    // Format expiry date
    document.getElementById('expiry-date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s*\/\s*|\s+/g, '').replace(/[^0-9]/gi, '');
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Format CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 3);
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.15);
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endsection
