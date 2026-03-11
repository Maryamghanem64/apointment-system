<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Appointment;
use App\Models\Provider;
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
    public function adminIndex(Request $request)
    {
        $query = Review::with(['user', 'provider.user']);

        // Filter by type (platform or provider)
        if ($request->has('type') && $request->type) {
            $query->where('review_type', $request->type);
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Filter by approval status
        if ($request->has('status') && $request->status) {
            $query->where('is_approved', $request->status === 'approved');
        }

        $reviews = $query->latest()->paginate(15);

        // Calculate stats
        $stats = [
            'total' => Review::count(),
            'platform' => Review::where('review_type', 'platform')->count(),
            'provider' => Review::where('review_type', 'provider')->count(),
            'average' => round(Review::where('is_approved', true)->avg('rating'), 1) ?? 0,
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show the form for creating a new review (client reviewing provider).
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
        
        // Check if already reviewed (by appointment_id for provider reviews)
        if (Review::where('appointment_id', $appointment->id)->where('review_type', 'provider')->exists()) {
            return redirect()->route('client.appointments')->with('error', 'This appointment has already been reviewed.');
        }
        
        return view('reviews.create', compact('appointment'));
    }

    /**
     * Show the form for creating a provider review (provider reviewing client).
     */
    public function createProviderReview(Appointment $appointment)
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        
        // Check if this provider owns the appointment
        if (!$provider || $appointment->provider_id !== $provider->id) {
            return redirect()->route('provider.appointments')->with('error', 'You can only review clients from your appointments.');
        }
        
        // Check if appointment is completed
        if ($appointment->status !== 'completed') {
            return redirect()->route('provider.appointments')->with('error', 'You can only review completed appointments.');
        }
        
        // Check if already reviewed
        if (Review::where('appointment_id', $appointment->id)->exists()) {
            return redirect()->route('provider.appointments')->with('error', 'This appointment has already been reviewed.');
        }
        
        return view('reviews.create-provider', compact('appointment'));
    }

    /**
     * Store a newly created review - handles both platform and provider reviews.
     */
    public function store(Request $request)
    {
        $request->validate([
            'review_type' => 'required|in:platform,provider',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'provider_id' => 'required_if:review_type,provider',
            'appointment_id' => 'required_if:review_type,provider',
        ]);

        // Prevent duplicate platform review per user
        if ($request->review_type === 'platform') {
            $exists = Review::where('user_id', auth()->id())
                ->where('review_type', 'platform')
                ->exists();
            if ($exists) {
                return back()->withErrors(['rating' => 'You have already reviewed the platform.']);
            }
        }

        // Prevent duplicate provider review per appointment
        if ($request->review_type === 'provider') {
            $exists = Review::where('appointment_id', $request->appointment_id)->exists();
            if ($exists) {
                return back()->withErrors(['rating' => 'You have already reviewed this appointment.']);
            }

            // Verify appointment belongs to user and is completed
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where('client_id', auth()->id())
                ->where('status', 'completed')
                ->first();
            
            if (!$appointment) {
                return back()->withErrors(['rating' => 'Invalid appointment.']);
            }
        }

        Review::create([
            'user_id' => auth()->id(),
            'review_type' => $request->review_type,
            'provider_id' => $request->review_type === 'provider' ? $request->provider_id : null,
            'appointment_id' => $request->review_type === 'provider' ? $request->appointment_id : null,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    /**
     * Store a provider review (legacy method for backward compatibility).
     */
    public function storeProviderReview(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        $appointment = Appointment::findOrFail($request->appointment_id);

        // Check if this provider owns the appointment
        if (!$provider || $appointment->provider_id !== $provider->id) {
            return back()->with('error', 'You can only review clients from your appointments.');
        }

        // Check if appointment is completed
        if ($appointment->status !== 'completed') {
            return back()->with('error', 'You can only review completed appointments.');
        }

        // Check if already reviewed
        if (Review::where('appointment_id', $appointment->id)->exists()) {
            return back()->with('error', 'This appointment has already been reviewed.');
        }

        // Create the review (provider reviewing client)
        Review::create([
            'user_id' => $appointment->client_id, // The client being reviewed
            'review_type' => 'provider',
            'provider_id' => $provider->id,
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return redirect()->route('provider.appointments')->with('success', 'Review submitted successfully!');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    /**
     * Toggle approve status of a review.
     */
    public function toggleApprove(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        return back()->with('success', 'Review updated.');
    }

    /**
     * Toggle featured status of a review.
     */
    public function toggleFeatured(Review $review)
    {
        $review->update(['is_featured' => !$review->is_featured]);
        return back()->with('success', 'Review updated.');
    }

    /**
     * Approve or unapprove a review (legacy method).
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
