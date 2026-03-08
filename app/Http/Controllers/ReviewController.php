<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of approved reviews (for welcome page).
     */
    public function index()
    {
        $reviews = Review::with(['user', 'provider.user'])
            ->where('is_approved', true)
            ->latest()
            ->get();

        return $reviews;
    }

    /**
     * Display all reviews for admin dashboard.
     */
    public function adminIndex()
    {
        $reviews = Review::with(['user', 'provider.user'])
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Appointment $appointment)
    {
        $user = Auth::user();
        
        // Check if user owns this appointment
        if ($appointment->client_id !== $user->id) {
            return redirect()->route('client.appointments')->with('error', 'You can only review your own appointments.');
        }
        
        // Check if appointment is completed
        if ($appointment->status !== 'completed') {
            return redirect()->route('client.appointments')->with('error', 'You can only review completed appointments.');
        }
        
        // Check if already reviewed
        if ($appointment->review()->exists()) {
            return redirect()->route('client.appointments')->with('error', 'This appointment has already been reviewed.');
        }
        
        return view('reviews.create', compact('appointment'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $appointment = Appointment::findOrFail($request->appointment_id);

        // Check if user owns this appointment
        if ($appointment->client_id !== $user->id) {
            return back()->with('error', 'You can only review your own appointments.');
        }

        // Check if appointment is completed
        if ($appointment->status !== 'completed') {
            return back()->with('error', 'You can only review completed appointments.');
        }

        // Check if already reviewed
        if ($appointment->review()->exists()) {
            return back()->with('error', 'This appointment has already been reviewed.');
        }

        // Create the review
        Review::create([
            'user_id' => $user->id,
            'provider_id' => $appointment->provider_id,
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true // Auto-approve for now
        ]);

        return redirect()->route('client.appointments')->with('success', 'Review submitted successfully!');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve or unapprove a review.
     */
    public function approve(Request $request, Review $review)
    {
        $request->validate([
            'is_approved' => 'required|boolean'
        ]);

        $review->update(['is_approved' => $request->is_approved]);

        $status = $request->is_approved ? 'approved' : 'unapproved';
        return redirect()->back()->with('success', "Review {$status} successfully.");
    }
}
