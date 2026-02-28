<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Dashboard
    public function index()
    {
        $user = Auth::user();
        $upcomingAppointments = Appointment::with('provider.user', 'service')
            ->where('client_id', $user->id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();
            
        $pastAppointments = Appointment::with('provider.user', 'service')
            ->where('client_id', $user->id)
            ->where('end_time', '<', now())
            ->count();
            
        $totalSpent = Appointment::where('client_id', $user->id)
            ->where('status', 'completed')
            ->count();
            
        return view('client.dashboard', compact('upcomingAppointments', 'pastAppointments', 'totalSpent'));
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
        $appointments = Appointment::with('provider.user', 'service')
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
