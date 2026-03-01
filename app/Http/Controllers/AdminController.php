<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Payment;

class AdminController extends Controller
{
   public function index()
   {
       // Get total counts with optimized queries
       $totalUsers = User::count();
       $totalProviders = Provider::count();
       $totalServices = Service::count();
       $totalAppointments = Appointment::count();
       $totalPayments = Payment::count();
       
       // Get appointment statistics grouped by status
       $appointmentStats = Appointment::select('status')
           ->selectRaw('COUNT(*) as count')
           ->groupBy('status')
           ->pluck('count', 'status')
           ->toArray();
       
       // Get payment statistics grouped by status
       $paymentStats = Payment::select('status')
           ->selectRaw('COUNT(*) as count')
           ->groupBy('status')
           ->pluck('count', 'status')
           ->toArray();
       
       // Get recent appointments with eager loading
       $recentAppointments = Appointment::with(['client', 'provider.user', 'service'])
           ->latest()
           ->take(5)
           ->get();
       
       return view('admin.dashboard', compact(
           'totalUsers', 
           'totalAppointments', 
           'totalServices', 
           'totalProviders', 
           'totalPayments',
           'appointmentStats',
           'paymentStats',
           'recentAppointments'
       ));
   }

   public function users()
   {
       $users = User::with('roles')->latest()->paginate(10);
       return view('admin.users', compact('users'));
   }
   
   public function settings()
   {
       return view('admin.settings');
   }
}
