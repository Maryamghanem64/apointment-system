<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProviderWorkingHour;
use App\Models\ProviderHoliday;

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
        
        // Get reviews for this provider
        $reviews = Review::with('user')
            ->where('provider_id', $provider->id)
            ->where('is_approved', true)
            ->latest()
            ->take(10)
            ->get();
        
        // Calculate average rating
        $averageRating = Review::where('provider_id', $provider->id)
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
        
        $totalReviews = Review::where('provider_id', $provider->id)
            ->where('is_approved', true)
            ->count();
        
        // Get completed appointments that haven't been reviewed by provider yet
        $appointmentsToReview = Appointment::with('client', 'service')
            ->where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review', function($query) {
                $query->where('reviewer_type', 'provider');
            })
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();
        
        return view('provider.dashboard', compact(
            'provider', 
            'todayAppointments', 
            'upcomingAppointments', 
            'totalAppointments', 
            'completedAppointments',
            'reviews',
            'averageRating',
            'totalReviews',
            'appointmentsToReview'
        ));
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
        // Optimize with eager loading for user, services, workingHours, and holidays
        $providers = Provider::with(['user', 'services', 'workingHours', 'holidays'])
            ->paginate(10);
        
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        // Get users who are not already providers
        $existingProviderUserIds = Provider::pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $existingProviderUserIds)->get();
        
        // Get all services for assignment
        $services = Service::all();
        
        return view('providers.create', compact('users', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:providers,user_id',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        $provider = Provider::create([
            'user_id' => $validated['user_id']
        ]);

        // Assign services if provided
        if (!empty($validated['service_ids'])) {
            $provider->services()->sync($validated['service_ids']);
        }

        // Save Working Hours
        if ($request->has('working_hours')) {
            foreach ($request->working_hours as $hours) {
                if (isset($hours['is_active']) && $hours['is_active'] == '1') {
                    ProviderWorkingHour::create([
                        'provider_id' => $provider->id,
                        'day_of_week' => array_search($hours['day'], ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
                        'start_time' => $hours['start_time'],
                        'end_time' => $hours['end_time'],
                    ]);
                }
            }
        }

        // Save Holidays
        if ($request->has('holidays')) {
            foreach ($request->holidays as $holiday) {
                if (!empty($holiday['date'])) {
                    \App\Models\ProviderHoliday::create([
                        'provider_id' => $provider->id,
                        'holiday_date' => $holiday['date'],
                        'reason' => $holiday['reason'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('providers.index')
            ->with('success','Provider created successfully');
    }

    public function edit(Provider $provider)
    {
        // Eager load relationships for the edit form
        $provider->load(['user', 'services', 'workingHours', 'holidays']);
        
        // Get users who are not already providers (excluding current provider)
        $existingProviderUserIds = Provider::where('id', '!=', $provider->id)->pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $existingProviderUserIds)->get();
        
        // Get all services for assignment
        $services = Service::all();
        
        $workingHours = $provider->workingHours->keyBy('day_of_week');
        $holidays = $provider->holidays;
        
        return view('providers.edit', compact('provider', 'users', 'services', 'workingHours', 'holidays'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:providers,user_id,' . $provider->id,
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        $provider->update([
            'user_id' => $validated['user_id']
        ]);

    // Update services if provided
    if (!empty($validated['service_ids'])) {
        $provider->services()->sync($validated['service_ids']);
    } else {
        $provider->services()->sync([]);
    }

    // Save Working Hours — delete old, insert new
    $provider->workingHours()->delete();
    if ($request->has('working_hours')) {
        $daysMap = array_flip(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        foreach ($request->working_hours as $hours) {
            if (isset($hours['is_active']) && $hours['is_active'] == '1') {
                \App\Models\ProviderWorkingHour::create([
                    'provider_id' => $provider->id,
                    'day_of_week' => $daysMap[$hours['day']],
                    'start_time'  => $hours['start_time'],
                    'end_time'    => $hours['end_time'],
                ]);
            }
        }
    }

    // Save Holidays — delete old, insert new
    $provider->holidays()->delete();
    if ($request->has('holidays')) {
        foreach ($request->holidays as $holiday) {
            if (!empty($holiday['holiday_date'])) {
                \App\Models\ProviderHoliday::create([
                    'provider_id' => $provider->id,
                    'holiday_date' => $holiday['holiday_date'],
                    'reason'       => $holiday['reason'] ?? null,
                ]);
            }
        }
    }

    return redirect()->route('providers.index')
        ->with('success','Provider updated successfully');
}

    public function destroy(Provider $provider)
    {
        // Check for future appointments
        $futureAppointmentsCount = Appointment::where('provider_id', $provider->id)
            ->where('start_time', '>=', now())
            ->count();

        if ($futureAppointmentsCount > 0) {
            return redirect()->route('providers.index')
                ->with('error', 'Cannot delete provider. They have ' . $futureAppointmentsCount . ' future appointment(s) scheduled.');
        }

        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success','Provider deleted successfully');
    }
}
