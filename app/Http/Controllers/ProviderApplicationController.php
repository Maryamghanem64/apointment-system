<?php

namespace App\Http\Controllers;

use App\Models\ProviderApplication;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderApplicationController extends Controller
{
    // Show application form (public)
    public function create()
    {
        return view('provider-application.create');
    }

    // Store application (public/guest)
    public function store(Request $request)
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:20',
            'specialty'  => 'required|string|max:255',
            'experience' => 'nullable|string|max:1000',
            'bio'        => 'nullable|string|max:1000',
        ]);

        // Check if already applied (pending or approved)
        $exists = ProviderApplication::where('email', $request->email)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'email' => 'An application with this email already exists.'
            ])->withInput();
        }

        $application = ProviderApplication::create([
            'user_id'    => auth()->id(),
            'full_name'  => $request->full_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'specialty'  => $request->specialty,
            'experience' => $request->experience,
            'bio'        => $request->bio,
            'status'     => 'pending',
        ]);

        // Notify admin by email (fail silently)
        try {
            \Mail::to(config('mail.from.address'))->send(
                new \App\Mail\NewProviderApplicationMail($request->full_name, $request->email)
            );
        } catch (\Exception $e) {
            // fail silently
            \Log::warning('Failed to send new provider application email: ' . $e->getMessage());
        }

        return redirect()->route('provider.application.success');
    }

    // Success page (public)
    public function success()
    {
        return view('provider-application.success');
    }

    // Admin — view all applications
    public function adminIndex()
    {
        $applications = ProviderApplication::with('user')
            ->latest()
            ->paginate(15);

        $stats = [
            'pending'  => ProviderApplication::where('status', 'pending')->count(),
            'approved' => ProviderApplication::where('status', 'approved')->count(),
            'rejected' => ProviderApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.applications.index', compact('applications', 'stats'));
    }

    // Admin — approve application
    public function approve(ProviderApplication $application)
    {
        $application->update(['status' => 'approved']);

        // If user exists → assign provider role & create provider record
        if ($application->user) {
            $application->user->assignRole('provider');

            // Create provider record
            Provider::firstOrCreate([
                'user_id' => $application->user_id
            ]);
        }

        // Notify applicant (fail silently)
        try {
            \Mail::to($application->email)->send(
                new \App\Mail\ProviderApplicationApprovedMail($application)
            );
        } catch (\Exception $e) {
            \Log::warning('Failed to send approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Application approved successfully.');
    }

    // Admin — reject application
    public function reject(ProviderApplication $application)
    {
        $application->update(['status' => 'rejected']);

        // Notify applicant (fail silently)
        try {
            \Mail::to($application->email)->send(
                new \App\Mail\ProviderApplicationRejectedMail($application)
            );
        } catch (\Exception $e) {
            \Log::warning('Failed to send rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Application rejected successfully.');
    }
}

