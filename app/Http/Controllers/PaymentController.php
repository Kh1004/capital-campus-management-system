<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentForm($courseId)
    {
        $course = Course::where('course_id', $courseId)->firstOrFail();
        $user = auth()->user();
        
        // Authorize the action
        $this->authorize('enroll', $course);
        
        // Check if user is already enrolled
        if ($user->enrolledCourses()->where('courses.course_id', $course->course_id)->exists()) {
            return redirect()->route('student.courses.show', $course->course_id)
                ->with('info', 'You are already enrolled in this course.');
        }

        return view('payments.payment', [
            'course' => $course,
            'amount' => $course->course_fee ?? 0,
        ]);
    }

    public function processPayment(Request $request, $courseId)
    {
        $course = Course::where('course_id', $courseId)->firstOrFail();
        $user = Auth::user();
        
        // Authorize the action
        $this->authorize('enroll', $course);
        
        // Check if already enrolled
        if ($user->enrolledCourses()->where('courses.course_id', $course->course_id)->exists()) {
            return redirect()->route('student.courses.show', $course->course_id)
                ->with('info', 'You are already enrolled in this course.');
        }
        
        // In a real application, validate payment details here
        // For this demo, we'll simulate a successful payment
        
        try {
            // Generate a random transaction ID
            $transactionId = 'TXN' . strtoupper(Str::random(10));
            
            // Create payment record
            $payment = new Payment([
                'user_id' => $user->id,
                'course_id' => $course->course_id,
                'amount' => $course->price ?? 999,
                'transaction_id' => $transactionId,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'payment_details' => [
                    'card_last_four' => '4242',  // Dummy last 4 digits
                    'card_brand' => 'visa',
                    'card_holder' => 'Dummy User',
                    'expiry_date' => '12/25'
                ]
            ]);
            
            $payment->save();

            // Enroll the user in the course
            $user->enrolledCourses()->attach($course->course_id, [
                'enrolled_at' => now(),
                'status' => 'enrolled',
                'payment_id' => $payment->id
            ]);

            // Redirect to course page with success message
            return redirect()->route('student.courses.show', $course->course_id)
                ->with('success', 'Payment successful! You are now enrolled in ' . $course->course_name);
                
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Payment processing error: ' . $e->getMessage());
            
            // Redirect back with error
            return redirect()->back()
                ->with('error', 'There was an error processing your payment. Please try again.')
                ->withInput();
        }
    }

    public function paymentSuccess($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        // Ensure the payment belongs to the authenticated user
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Load relationships
        $payment->load(['course', 'user']);

        return view('payments.success', [
            'payment' => $payment,
            'course' => $payment->course,
            'user' => $payment->user
        ]);
    }

    public function paymentFailure(Request $request)
    {
        $courseId = $request->session()->pull('course_id');
        $course = $courseId ? Course::find($courseId) : null;
        
        return view('payments.failure', [
            'course' => $course
        ]);
    }

    /**
     * Get the card brand from the card number
     */
    private function getCardBrand($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5][0-9]{14}$|^2(?:2(?:2[1-9]|[3-9][0-9])|[3-6][0-9][0-9]|7(?:[01][0-9]|20))[0-9]{12}$/', $number)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47][0-9]{13}$/', $number)) {
            return 'American Express';
        } else {
            return 'Unknown';
        }
    }
}
