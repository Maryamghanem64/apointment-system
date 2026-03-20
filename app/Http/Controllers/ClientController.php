<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Dashboard
    public function index()
    {
        $user = Auth::user();
        
        $upcomingCount = $user->appointments()->whereIn('status', ['pending','confirmed'])->count();
        $pendingCount = $user->appointments()->where('status', 'pending')->count();
        $completedCount = $user->appointments()->where('status', 'completed')->count();
        $cancelledCount = $user->appointments()->where('status', 'cancelled')->count();
        
$nextAppointment = $user->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
->where('start_time', '>=', now())
            ->with('provider.user', 'service', 'payment')
            ->orderBy('start_time')
            ->first();
        
        $recentAppointments = $user->appointments()
            ->with('provider.user', 'service', 'review')
            ->latest()
            ->take(5)
            ->get();
        
        $providers = \App\Models\Provider::with(['user', 'reviews', 'services'])
            ->take(6)
            ->get();
        
        $upcomingAppointments = Appointment::with('provider.user', 'service')
            ->where('client_id', $user->id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();
            
        // Get user's reviews
        $myReviews = Review::with('provider.user')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('client.dashboard', compact(
            'upcomingCount', 'pendingCount', 'completedCount', 'cancelledCount',
            'nextAppointment', 'recentAppointments', 'providers',
            'upcomingAppointments', 'myReviews'
        ));
    }

    // Profile
    public function profile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }

    // Appointments
    public function appointments()
    {
        $user = Auth::user();
        $appointments = Appointment::with(['provider.user', 'service', 'review'])
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return view('client.appointments', compact('appointments'));
    }

    // Settings
    public function settings()
    {
        return view('client.settings');
    }
}
