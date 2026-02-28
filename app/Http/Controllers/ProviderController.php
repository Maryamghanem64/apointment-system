<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    // Provider Dashboard (for provider role)
    public function dashboard()
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        
        if (!$provider) {
            return redirect()->route('dashboard')->with('error', 'Provider profile not found.');
        }
        
        $todayAppointments = Appointment::with('client', 'service')
            ->where('provider_id', $provider->id)
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();
        
        $upcomingAppointments = Appointment::with('client', 'service')
            ->where('provider_id', $provider->id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();
        
        $totalAppointments = Appointment::where('provider_id', $provider->id)->count();
        $completedAppointments = Appointment::where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->count();
        
        return view('provider.dashboard', compact('provider', 'todayAppointments', 'upcomingAppointments', 'totalAppointments', 'completedAppointments'));
    }
    
    // Provider Profile (for provider role)
    public function providerProfile()
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        return view('provider.profile', compact('provider', 'user'));
    }
    
    // Provider Appointments (for provider role)
    public function providerAppointments()
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        
        if (!$provider) {
            return redirect()->route('dashboard')->with('error', 'Provider profile not found.');
        }
        
        $appointments = Appointment::with('client', 'service')
            ->where('provider_id', $provider->id)
            ->latest()
            ->paginate(10);
            
        return view('provider.appointments', compact('appointments', 'provider'));
    }
    
    // Provider Settings (for provider role)
    public function providerSettings()
    {
        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();
        return view('provider.settings', compact('provider'));
    }

    // Admin: Providers Management
    public function index()
    {
        $providers = Provider::with('user')->paginate(10);
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        $users = User::all();
        return view('providers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:providers,user_id',
        ]);

        Provider::create($validated);

        return redirect()->route('providers.index')
            ->with('success','Provider created successfully');
    }

    public function edit(Provider $provider)
    {
        $users = User::all();
        return view('providers.edit', compact('provider','users'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:providers,user_id,' . $provider->id,
        ]);

        $provider->update($validated);

        return redirect()->route('providers.index')
            ->with('success','Provider updated successfully');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success','Provider deleted successfully');
    }
}
